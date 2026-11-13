<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\AccommodationCategory;
use App\Models\Facility;
use App\Models\Rule;
use App\Services\AccommodationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccommodationController extends Controller
{
    protected AccommodationService $accommodationService;

    public function __construct(
        AccommodationService $accommodationService
    ) {
        $this->accommodationService = $accommodationService;
    }

    /**
     * Daftar penginapan untuk admin.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $categoryId = $request->input('category_id');

        $accommodations = $this->accommodationService
            ->getAccommodations($keyword, 5, $categoryId);

        $categories = AccommodationCategory::orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();
        $rules = Rule::orderBy('name')->get();

        return view(
            'example.content.accomodation.penginapan',
            compact(
                'accommodations',
                'categories',
                'facilities',
                'rules'
            )
        );
    }

    /**
     * Halaman publik: semua penginapan.
     */
    public function publicIndex(Request $request)
    {
        $keyword = $request->input('keyword');
        $categoryId = $request->input('category_id') ?? $request->input('category');
        $facilityIds = (array) $request->input('facility_id', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $query = Accommodation::with(['category', 'ratings', 'facilities'])
            ->when($keyword, function ($q, $keyword) {
                $q->where(function ($sub) use ($keyword) {
                    $sub->where('title', 'like', "%{$keyword}%")
                        ->orWhere('address', 'like', "%{$keyword}%");
                });
            })
            ->when($categoryId, function ($q, $categoryId) {
                if (is_numeric($categoryId)) {
                    $q->where('category_id', $categoryId);
                } else {
                    $q->whereHas('category', function ($sub) use ($categoryId) {
                        $sub->where('slug', $categoryId);
                    });
                }
            })
            ->when(!empty($facilityIds), function ($q) use ($facilityIds) {
                $q->whereHas('facilities', function ($sub) use ($facilityIds) {
                    $sub->whereIn('facilities.id', $facilityIds);
                });
            })
            ->when($minPrice, function ($q, $minPrice) {
                $q->where('price', '>=', (float) $minPrice);
            })
            ->when($maxPrice && (float) $maxPrice < 10000000 && (float) $maxPrice > 0, function ($q) use ($maxPrice) {
                $q->where('price', '<=', (float) $maxPrice);
            })
            ->latest();

        $accommodations = $query->paginate(12)->withQueryString();
        $categories = AccommodationCategory::orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();

        $siteName = 'adakamar.id';
        $title = 'Semua Penginapan | ' . $siteName;
        $metaDescription = 'Temukan berbagai pilihan penginapan terbaik dan nyaman di ' . $siteName;

        return view(
            'landingpage.content.accomodation.semua_penginapan',
            compact(
                'accommodations',
                'categories',
                'facilities',
                'title',
                'metaDescription'
            )
        );
    }

    /**
     * Daftar penginapan berdasarkan kategori + SEO.
     */
    public function byCategory(string $slug)
    {
        $data = $this->accommodationService
            ->getByCategorySlug($slug);

        if (!$data) {
            abort(404);
        }

        $category = $data['category'];
        $siteName = 'adakamar.id';

        $title = $category->name . ' - Penginapan | ' . $siteName;

        $metaDescription = trim(strip_tags((string) $category->description));

        if ($metaDescription === '') {
            $metaDescription = 'Temukan pilihan penginapan kategori '
                . $category->name
                . ' di adakamar.id. Lihat informasi, harga, fasilitas, '
                . 'dan lokasi penginapan.';
        }

        $metaDescription = preg_replace('/\s+/u', ' ', $metaDescription);
        $metaDescription = Str::limit($metaDescription, 160, '');

        $canonical = url()->current();

        $ogImage = $category->thumbnail ? asset($category->thumbnail) : null;

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $title,
            'description' => $metaDescription,
            'url' => $canonical,
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => url('/'),
            ],
        ];

        if ($ogImage) {
            $structuredData['primaryImageOfPage'] = [
                '@type' => 'ImageObject',
                'url' => $ogImage,
            ];
        }

        return view(
            'landingpage.content.accomodation.kamar',
            [
                'category' => $category,
                'accommodations' => $data['accommodations'],
                'title' => $title,
                'metaTitle' => $title,
                'metaDescription' => $metaDescription,
                'metaAuthor' => $siteName,
                'metaKeywords' => $category->name . ', penginapan, akomodasi',
                'canonical' => $canonical,
                'robots' => 'index, follow',
                'ogTitle' => $title,
                'ogDescription' => $metaDescription,
                'ogImage' => $ogImage,
                'ogImageAlt' => $category->name,
                'ogType' => 'website',
                'structuredData' => $structuredData,
            ]
        );
    }

    /**
     * Detail penginapan untuk landing page + SEO.
     */
    public function publicDetail(string $slug)
    {
        $accommodation = $this->accommodationService
            ->getAccommodationBySlug($slug);

        if (!$accommodation) {
            abort(404);
        }

        $accommodation->load([
            'category',
            'images',
            'facilities',
            'rules',
            'ratings',
        ]);

        $siteName = 'adakamar.id';

        $customTitle = trim((string) $accommodation->meta_title);
        $pageTitle = $customTitle !== '' ? $customTitle : $accommodation->title;
        $title = $pageTitle . ' | ' . $siteName;

        $metaDescription = trim(strip_tags((string) $accommodation->meta_description));

        if ($metaDescription === '') {
            $metaDescription = trim(strip_tags((string) $accommodation->description));
        }

        if ($metaDescription === '') {
            $metaDescription = 'Informasi penginapan '
                . $accommodation->title
                . ', harga, fasilitas, alamat, dan detail lainnya '
                . 'di adakamar.id.';
        }

        $metaDescription = preg_replace('/\s+/u', ' ', $metaDescription);
        $metaDescription = Str::limit($metaDescription, 160, '');

        $canonical = url()->current();

        $ogImage = $accommodation->thumbnail
            ? asset($accommodation->thumbnail)
            : null;

        if (!$ogImage) {
            $firstImage = $accommodation->images->first();
            if ($firstImage && $firstImage->image) {
                $ogImage = asset($firstImage->image);
            }
        }

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'LodgingBusiness',
            'name' => $accommodation->title,
            'description' => $metaDescription,
            'url' => $canonical,
        ];

        if ($ogImage) {
            $structuredData['image'] = [$ogImage];
        }

        if ($accommodation->address) {
            $structuredData['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $accommodation->address,
                'addressCountry' => 'ID',
            ];
        }

        if (is_numeric($accommodation->price) && (float) $accommodation->price >= 0) {
            $structuredData['priceRange'] = 'IDR '
                . number_format((float) $accommodation->price, 0, ',', '.');
        }

        if ($accommodation->category) {
            $structuredData['category'] = $accommodation->category->name;
        }

        return view(
            'landingpage.content.accomodation.detail_penginapan',
            compact(
                'accommodation',
                'title',
                'metaDescription',
                'canonical',
                'ogImage',
                'structuredData'
            )
        )->with([
            'metaTitle' => $title,
            'metaAuthor' => $siteName,
            'metaKeywords' => implode(', ', array_filter([
                $accommodation->title,
                $accommodation->category?->name,
                'penginapan',
                'akomodasi',
            ])),
            'ogTitle' => $title,
            'ogDescription' => $metaDescription,
            'ogImageAlt' => $accommodation->title,
            'ogType' => 'website',
            'robots' => 'index, follow',
        ]);
    }

    /**
     * Detail penginapan untuk admin / AJAX.
     */
    public function show(int $id)
    {
        $accommodation = $this->accommodationService
            ->getAccommodation($id);

        if (!$accommodation) {
            return response()->json([
                'success' => false,
                'message' => 'Penginapan tidak ditemukan.',
            ], 404);
        }

        $accommodation->load([
            'category',
            'images',
            'facilities',
            'rules',
            'ratings',
        ]);

        $data = $accommodation->toArray();

        $data['facility_ids'] = $accommodation->facilities->pluck('id')->toArray();
        $data['rule_ids'] = $accommodation->rules->pluck('id')->toArray();

        $data['images'] = $accommodation->images
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'image' => $item->image,
                    'image_url' => $item->image ? asset($item->image) : null,
                ];
            })
            ->values();

        $data['thumbnail_url'] = $accommodation->thumbnail
            ? asset($accommodation->thumbnail)
            : null;

        $data['whatsapp_url'] = $accommodation->whatsapp_url;
        $data['phone_url'] = $accommodation->phone_url;
        $data['email_url'] = $accommodation->email_url;

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Menyimpan penginapan.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate($this->storeRules());

            $accommodation = $this->accommodationService
                ->createAccommodation($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Penginapan berhasil ditambahkan.',
                    'data' => $accommodation,
                ]);
            }

            return redirect()
                ->route('accommodations.index')
                ->with('success', 'Penginapan berhasil ditambahkan.');

        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;

        } catch (\Throwable $e) {
            report($e);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan penginapan.',
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan penginapan.');
        }
    }

    /**
     * Update penginapan.
     *
     * ⚠️ PERHATIAN: Di method ini ada logika hapus galeri.
     * - `deleted_gallery_ids[]`   → ID foto existing yang dihapus user (klik "X")
     * - `existing_gallery_ids[]`  → ID foto existing yang masih dipertahankan
     * - `existing_gallery_ids_present` → penanda bahwa field di atas dikirim
     */
    public function update(Request $request, int $id)
    {
        try {
            $validated = $request->validate($this->updateRules());

            // Cari data penginapan dulu
            $accommodation = Accommodation::find($id);

            if (!$accommodation) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Penginapan tidak ditemukan.',
                    ], 404);
                }

                return redirect()
                    ->route('accommodations.index')
                    ->with('error', 'Penginapan tidak ditemukan.');
            }

            // =========================================================
            // ✅ HAPUS FOTO GALERI YANG DIKLIK "X" OLEH USER
            // =========================================================
            $deletedIds = $request->input('deleted_gallery_ids', []);

            if (!empty($deletedIds) && is_array($deletedIds)) {
                $imagesToDelete = $accommodation->images()
                    ->whereIn('id', $deletedIds)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    // Hapus file fisik dari public/
                    if ($image->image) {
                        $filePath = public_path($image->image);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }

                        // Hapus file fisik dari storage/app/public/
                        if (Storage::disk('public')->exists($image->image)) {
                            Storage::disk('public')->delete($image->image);
                        }
                    }

                    // Hapus record dari database
                    $image->delete();
                }
            }

            // =========================================================
            // ✅ HAPUS FOTO GALERI EXISTING YANG TIDAK ADA DI KEPT IDS
            // (safety net: user hapus foto, tapi deleted_gallery_ids
            //  tidak terkirim karena JS error / network)
            // =========================================================
            if ($request->has('existing_gallery_ids_present')) {
                $keptIds = $request->input('existing_gallery_ids', []);

                $imagesToDelete = $accommodation->images()
                    ->whereNotIn('id', $keptIds)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    if ($image->image) {
                        $filePath = public_path($image->image);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }

                        if (Storage::disk('public')->exists($image->image)) {
                            Storage::disk('public')->delete($image->image);
                        }
                    }

                    $image->delete();
                }

                // Teruskan ke service supaya service bisa tahu
                // foto lama mana yang dipertahankan
                $validated['existing_gallery_ids'] = $keptIds;
            }

            // =========================================================
            // UPDATE DATA PENGINAPAN LEWAT SERVICE
            // =========================================================
            $accommodation = $this->accommodationService
                ->updateAccommodation($id, $validated);

            if (!$accommodation) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Penginapan tidak ditemukan.',
                    ], 404);
                }

                return redirect()
                    ->route('accommodations.index')
                    ->with('error', 'Penginapan tidak ditemukan.');
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Penginapan berhasil diperbarui.',
                    'data' => $accommodation,
                ]);
            }

            return redirect()
                ->route('accommodations.index')
                ->with('success', 'Penginapan berhasil diperbarui.');

        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;

        } catch (\Throwable $e) {
            report($e);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui penginapan: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui penginapan.');
        }
    }

    /**
     * Hapus penginapan.
     */
    public function destroy(int $id)
    {
        try {
            $deleted = $this->accommodationService
                ->deleteAccommodation($id);

            if (!$deleted) {
                return redirect()
                    ->route('accommodations.index')
                    ->with('error', 'Penginapan tidak ditemukan.');
            }

            return redirect()
                ->route('accommodations.index')
                ->with('success', 'Penginapan berhasil dihapus.');

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus penginapan.');
        }
    }

    /**
     * Validasi create.
     */
    protected function storeRules(): array
    {
        return [
            'category_id' => ['required', 'exists:accommodation_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1048576'],
            'price' => ['required', 'numeric', 'min:0'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'x' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'maps_embed' => ['nullable', 'string'],
            'google_maps_url' => ['nullable', 'url', 'max:2048'],
            'capacity' => ['required', 'integer', 'min:1'],
            'bedroom' => ['required', 'integer', 'min:0'],
            'bathroom' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:Available,Full,Maintenance'],
            'description' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'facility_ids' => ['nullable', 'array'],
            'facility_ids.*' => ['exists:facilities,id'],
            'rule_ids' => ['nullable', 'array'],
            'rule_ids.*' => ['exists:rules,id'],
            'gallery' => ['required', 'array', 'min:2', 'max:10'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:1048576'],
            'existing_gallery_ids' => ['nullable', 'array'],
            'existing_gallery_ids.*' => ['integer', 'exists:accommodation_images,id'],
            'deleted_gallery_ids' => ['nullable', 'array'],
            'deleted_gallery_ids.*' => ['integer', 'exists:accommodation_images,id'],
        ];
    }

    /**
     * Validasi update.
     */
    protected function updateRules(): array
    {
        return [
            'category_id' => ['required', 'exists:accommodation_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1048576'],
            'price' => ['required', 'numeric', 'min:0'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'x' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'maps_embed' => ['nullable', 'string'],
            'google_maps_url' => ['nullable', 'url', 'max:2048'],
            'capacity' => ['required', 'integer', 'min:1'],
            'bedroom' => ['required', 'integer', 'min:0'],
            'bathroom' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:Available,Full,Maintenance'],
            'description' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'facility_ids' => ['nullable', 'array'],
            'facility_ids.*' => ['exists:facilities,id'],
            'rule_ids' => ['nullable', 'array'],
            'rule_ids.*' => ['exists:rules,id'],
            'gallery' => ['nullable', 'array', 'max:10'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:1048576'],
            'existing_gallery_ids' => ['nullable', 'array'],
            'existing_gallery_ids.*' => ['integer', 'exists:accommodation_images,id'],
            'deleted_gallery_ids' => ['nullable', 'array'],
            'deleted_gallery_ids.*' => ['integer', 'exists:accommodation_images,id'],
        ];
    }
}