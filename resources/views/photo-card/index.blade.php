<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>News Photo Card Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">News Photo Card Generator</h1>
                <p class="text-gray-600">Create beautiful social media cards with customization options</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-6">
                            <form id="photoCardForm" enctype="multipart/form-data">
                                <div class="space-y-5">
                                    <div>
                                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                            News Title
                                        </label>
                                        <input 
                                            type="text" 
                                            id="title" 
                                            name="title" 
                                            placeholder="Enter your news title here..."
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                            required
                                            maxlength="200"
                                        >
                                        <p class="text-xs text-gray-500 mt-1">Maximum 200 characters</p>
                                    </div>

                                    <div>
                                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Background Image
                                        </label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition cursor-pointer" id="dropZone">
                                            <input 
                                                type="file" 
                                                id="image" 
                                                name="image" 
                                                accept="image/*"
                                                class="hidden"
                                                required
                                            >
                                            <div id="uploadPlaceholder" class="text-gray-500">
                                                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <p class="text-sm">Click to upload or drag and drop</p>
                                                <p class="text-xs text-gray-400 mt-1">PNG, JPG (max 5MB)</p>
                                            </div>
                                            <div id="imagePreview" class="hidden">
                                                <img id="previewImage" class="max-h-40 mx-auto rounded-lg shadow-md mb-2">
                                                <button type="button" id="removeImage" class="text-red-600 hover:text-red-700 text-sm">Remove image</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="brand_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Brand / Site Name
                                            </label>
                                            <input 
                                                type="text" 
                                                id="brand_name" 
                                                name="brand_name" 
                                                placeholder="e.g., Daily News, Tech Daily"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                                maxlength="50"
                                            >
                                        </div>

                                        <div>
                                            <label for="brand_logo" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Brand Logo (Optional)
                                            </label>
                                            <div class="border border-gray-300 rounded-lg p-2 text-center">
                                                <input 
                                                    type="file" 
                                                    id="brand_logo" 
                                                    name="brand_logo" 
                                                    accept="image/png,image/jpeg,image/svg+xml"
                                                    class="text-sm w-full"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Template Style
                                        </label>
                                        <div class="grid grid-cols-5 gap-2">
                                            <label class="template-option cursor-pointer">
                                                <input type="radio" name="template" value="standard" class="sr-only peer" checked>
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                                    <div class="w-full h-16 bg-gradient-to-t from-gray-800 to-transparent rounded mb-2 relative">
                                                        <div class="absolute bottom-1 left-1 right-1 h-6 bg-gray-600 rounded"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-gray-600">Standard</span>
                                                </div>
                                            </label>
                                            <label class="template-option cursor-pointer">
                                                <input type="radio" name="template" value="fullwidth" class="sr-only peer">
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                                    <div class="w-full h-16 bg-gradient-to-t from-gray-800/80 to-transparent rounded mb-2 relative">
                                                        <div class="absolute inset-0 bg-blue-200/30 rounded"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-gray-600">Full Width</span>
                                                </div>
                                            </label>
                                            <label class="template-option cursor-pointer">
                                                <input type="radio" name="template" value="split-left" class="sr-only peer">
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                                    <div class="flex h-16 rounded mb-2 overflow-hidden">
                                                        <div class="w-1/2 bg-gray-800 relative">
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <div class="w-6 h-2 bg-white rounded"></div>
                                                            </div>
                                                        </div>
                                                        <div class="w-1/2 bg-blue-900"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-gray-600">Split Left</span>
                                                </div>
                                            </label>
                                            <label class="template-option cursor-pointer">
                                                <input type="radio" name="template" value="split-right" class="sr-only peer">
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                                    <div class="flex h-16 rounded mb-2 overflow-hidden">
                                                        <div class="w-1/2 bg-blue-900"></div>
                                                        <div class="w-1/2 bg-gray-800 relative">
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <div class="w-6 h-2 bg-white rounded"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs font-medium text-gray-600">Split Right</span>
                                                </div>
                                            </label>
                                            <label class="template-option cursor-pointer">
                                                <input type="radio" name="template" value="minimal" class="sr-only peer">
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                                    <div class="w-full h-16 bg-gray-400 rounded mb-2 relative">
                                                        <div class="absolute bottom-0 left-0 right-0 h-4 bg-gray-800/70 rounded-b"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-gray-600">Minimal</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="font" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Font Style
                                            </label>
                                            <select 
                                                id="font" 
                                                name="font"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                                <option value="noto">Noto Sans (English)</option>
                                                <option value="bangla">Noto Sans (Bangla)</option>
                                                <option value="roboto">Roboto</option>
                                                <option value="poppins">Poppins</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="text_position" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Text Position
                                            </label>
                                            <select 
                                                id="text_position" 
                                                name="text_position"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                                <option value="bottom">Bottom</option>
                                                <option value="center">Center</option>
                                                <option value="top">Top</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="overlay_color" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Overlay Color
                                            </label>
                                            <div class="flex gap-2">
                                                <input 
                                                    type="color" 
                                                    id="overlay_color_picker" 
                                                    value="#000000"
                                                    class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                                                >
                                                <input 
                                                    type="text" 
                                                    id="overlay_color" 
                                                    name="overlay_color"
                                                    value="#000000"
                                                    placeholder="#000000"
                                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                >
                                            </div>
                                        </div>

                                        <div>
                                            <label for="overlay_opacity" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Overlay Opacity: <span id="opacity_value">70</span>%
                                            </label>
                                            <input 
                                                type="range" 
                                                id="overlay_opacity" 
                                                name="overlay_opacity"
                                                min="0" 
                                                max="100" 
                                                value="70"
                                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                            >
                                        </div>
                                    </div>

                                    <div>
                                        <label for="logo_position" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Logo Position
                                        </label>
                                        <div class="grid grid-cols-5 gap-2">
                                            <label class="logo-option cursor-pointer">
                                                <input type="radio" name="logo_position" value="top-left" class="sr-only peer" checked>
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition h-16 relative">
                                                    <div class="absolute top-2 left-2 w-4 h-4 bg-blue-600 rounded"></div>
                                                </div>
                                            </label>
                                            <label class="logo-option cursor-pointer">
                                                <input type="radio" name="logo_position" value="top-right" class="sr-only peer">
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition h-16 relative">
                                                    <div class="absolute top-2 right-2 w-4 h-4 bg-blue-600 rounded"></div>
                                                </div>
                                            </label>
                                            <label class="logo-option cursor-pointer">
                                                <input type="radio" name="logo_position" value="bottom-left" class="sr-only peer">
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition h-16 relative">
                                                    <div class="absolute bottom-2 left-2 w-4 h-4 bg-blue-600 rounded"></div>
                                                </div>
                                            </label>
                                            <label class="logo-option cursor-pointer">
                                                <input type="radio" name="logo_position" value="bottom-right" class="sr-only peer">
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition h-16 relative">
                                                    <div class="absolute bottom-2 right-2 w-4 h-4 bg-blue-600 rounded"></div>
                                                </div>
                                            </label>
                                            <label class="logo-option cursor-pointer">
                                                <input type="radio" name="logo_position" value="center" class="sr-only peer">
                                                <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition h-16 relative">
                                                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-blue-600 rounded"></div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-3 pt-3">
                                        <button 
                                            type="button" 
                                            id="previewBtn"
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Preview
                                        </button>
                                        
                                        <button 
                                            type="button" 
                                            id="generateBtn"
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Generate & Save
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div id="previewModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
                                <div class="bg-white rounded-lg shadow-2xl max-w-5xl w-full">
                                    <div class="p-4 border-b flex justify-between items-center">
                                        <h3 class="text-lg font-semibold">Preview</h3>
                                        <button id="closePreview" class="text-gray-500 hover:text-gray-700">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-6">
                                        <img id="previewCard" class="w-full h-auto rounded-lg shadow-lg">
                                    </div>
                                </div>
                            </div>

                            <div id="successMessage" class="hidden mt-5 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-semibold text-green-800">Card generated successfully!</p>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <a id="downloadLink" href="#" target="_blank" class="inline-flex items-center gap-1 text-sm text-green-700 hover:text-green-800 bg-green-100 px-3 py-1 rounded-full">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div id="shareButtons" class="mt-4 pt-4 border-t border-green-200">
                                    <p class="text-sm font-semibold text-green-800 mb-3">Share on Social Media:</p>
                                    <div class="flex flex-wrap gap-3">
                                        <a id="shareFacebook" href="#" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-md">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                            Facebook
                                        </a>
                                        <a id="shareTwitter" href="#" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition text-sm font-medium shadow-md">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                            Twitter/X
                                        </a>
                                        <a id="shareLinkedIn" href="#" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium shadow-md">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                            LinkedIn
                                        </a>
                                        <a id="shareWhatsApp" href="#" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium shadow-md">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div id="errorMessage" class="hidden mt-5 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p id="errorText" class="text-red-800"></p>
                                </div>
                            </div>

                            <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">
                                <div class="bg-white rounded-lg p-8 shadow-xl">
                                    <div class="flex items-center gap-4">
                                        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-lg font-semibold text-gray-700">Processing...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Presets</h2>
                        <div class="space-y-2">
                            <button type="button" onclick="applyPreset('dark')" class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-gray-800 to-gray-900 rounded"></div>
                                    <div>
                                        <p class="font-medium text-gray-800">Dark Theme</p>
                                        <p class="text-xs text-gray-500">Black overlay, white text</p>
                                    </div>
                                </div>
                            </button>
                            <button type="button" onclick="applyPreset('blue')" class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-800 to-blue-900 rounded"></div>
                                    <div>
                                        <p class="font-medium text-gray-800">Blue Theme</p>
                                        <p class="text-xs text-gray-500">Blue overlay, white text</p>
                                    </div>
                                </div>
                            </button>
                            <button type="button" onclick="applyPreset('red')" class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-800 to-red-900 rounded"></div>
                                    <div>
                                        <p class="font-medium text-gray-800">Red Theme</p>
                                        <p class="text-xs text-gray-500">Red overlay, white text</p>
                                    </div>
                                </div>
                            </button>
                            <button type="button" onclick="applyPreset('gradient')" class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded"></div>
                                    <div>
                                        <p class="font-medium text-gray-800">Gradient Theme</p>
                                        <p class="text-xs text-gray-500">Purple-pink gradient</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Features</h2>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-800 text-sm">5 Template Styles</h3>
                                    <p class="text-xs text-gray-600">Standard, Full Width, Split, Minimal</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-800 text-sm">Bangla + English</h3>
                                    <p class="text-xs text-gray-600">Automatic language detection</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <h3 class="font-sem-semibold text-gray-800 text-sm">Brand Logo & Name</h3>
                                    <p class="text-xs text-gray-600">Add your brand identity</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-800 text-sm">Social Share</h3>
                                    <p class="text-xs text-gray-600">Share directly to social media</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-800 text-sm">Social Ready</h3>
                                    <p class="text-xs text-gray-600">1200x630 optimal dimensions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('photoCardForm');
        const imageInput = document.getElementById('image');
        const dropZone = document.getElementById('dropZone');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        const previewBtn = document.getElementById('previewBtn');
        const generateBtn = document.getElementById('generateBtn');
        const previewModal = document.getElementById('previewModal');
        const previewCard = document.getElementById('previewCard');
        const closePreview = document.getElementById('closePreview');
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const downloadLink = document.getElementById('downloadLink');
        const removeImage = document.getElementById('removeImage');
        const overlayColorPicker = document.getElementById('overlay_color_picker');
        const overlayColorInput = document.getElementById('overlay_color');
        const overlayOpacitySlider = document.getElementById('overlay_opacity');
        const opacityValue = document.getElementById('opacity_value');
        const shareFacebook = document.getElementById('shareFacebook');
        const shareTwitter = document.getElementById('shareTwitter');
        const shareLinkedIn = document.getElementById('shareLinkedIn');
        const shareWhatsApp = document.getElementById('shareWhatsApp');

        let generatedImageUrl = '';

        overlayColorPicker.addEventListener('input', (e) => {
            overlayColorInput.value = e.target.value;
        });
        overlayColorInput.addEventListener('input', (e) => {
            if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
                overlayColorPicker.value = e.target.value;
            }
        });
        overlayOpacitySlider.addEventListener('input', (e) => {
            opacityValue.textContent = e.target.value;
        });

        function applyPreset(preset) {
            const presets = {
                dark: { color: '#000000', opacity: 70 },
                blue: { color: '#1e3a8a', opacity: 70 },
                red: { color: '#991b1b', opacity: 70 },
                gradient: { color: '#7c3aed', opacity: 60 }
            };
            const p = presets[preset];
            overlayColorPicker.value = p.color;
            overlayColorInput.value = p.color;
            overlayOpacitySlider.value = p.opacity;
            opacityValue.textContent = p.opacity;
        }

        dropZone.addEventListener('click', () => imageInput.click());
        
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                displayImage(file);
            }
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                imageInput.files = e.dataTransfer.files;
                displayImage(file);
            }
        });

        function displayImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                uploadPlaceholder.classList.add('hidden');
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        removeImage.addEventListener('click', (e) => {
            e.stopPropagation();
            imageInput.value = '';
            uploadPlaceholder.classList.remove('hidden');
            imagePreview.classList.add('hidden');
            previewImage.src = '';
        });

        previewBtn.addEventListener('click', async () => {
            if (!validateForm()) return;
            
            showLoading();
            hideMessages();

            const formData = new FormData(form);

            try {
                const response = await fetch('/photo-card/preview', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Preview generation failed');
                }

                const blob = await response.blob();
                const url = URL.createObjectURL(blob);
                previewCard.src = url;
                previewModal.classList.remove('hidden');
            } catch (error) {
                showError(error.message);
            } finally {
                hideLoading();
            }
        });

        generateBtn.addEventListener('click', async () => {
            if (!validateForm()) return;
            
            showLoading();
            hideMessages();

            const formData = new FormData(form);

            try {
                const response = await fetch('/photo-card/generate', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Generation failed');
                }

                generatedImageUrl = window.location.origin + data.image_url;
                const title = document.getElementById('title').value;
                
                downloadLink.href = data.image_url;
                downloadLink.download = 'news-card.jpg';
                
                shareFacebook.href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(generatedImageUrl)}&quote=${encodeURIComponent(title)}`;
                shareTwitter.href = `https://twitter.com/intent/tweet?url=${encodeURIComponent(generatedImageUrl)}&text=${encodeURIComponent(title)}`;
                shareLinkedIn.href = `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(generatedImageUrl)}&title=${encodeURIComponent(title)}`;
                shareWhatsApp.href = `https://wa.me/?text=${encodeURIComponent(title + ' ' + generatedImageUrl)}`;
                
                successMessage.classList.remove('hidden');
            } catch (error) {
                showError(error.message);
            } finally {
                hideLoading();
            }
        });

        closePreview.addEventListener('click', () => {
            previewModal.classList.add('hidden');
        });

        previewModal.addEventListener('click', (e) => {
            if (e.target === previewModal) {
                previewModal.classList.add('hidden');
            }
        });

        function validateForm() {
            const title = document.getElementById('title').value.trim();
            const image = imageInput.files[0];

            if (!title) {
                showError('Please enter a title');
                return false;
            }

            if (!image) {
                showError('Please select an image');
                return false;
            }

            return true;
        }

        function showLoading() {
            loadingOverlay.classList.remove('hidden');
            previewBtn.disabled = true;
            generateBtn.disabled = true;
        }

        function hideLoading() {
            loadingOverlay.classList.add('hidden');
            previewBtn.disabled = false;
            generateBtn.disabled = false;
        }

        function hideMessages() {
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');
        }

        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
        }
    </script>
</body>
</html>
