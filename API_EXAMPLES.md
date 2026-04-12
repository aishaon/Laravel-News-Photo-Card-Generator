# API Usage Examples

## Web Interface (Browser)

Open your browser and navigate to:
```
http://localhost:8000/photo-card
```

Or simply visit:
```
http://localhost:8000/
```

## REST API Examples

### 1. PHP (cURL)

```php
<?php

$url = 'http://localhost:8000/api/photo-card';
$title = 'আজকের সংবাদ - আজকের খবর';
$imagePath = '/path/to/your/image.jpg';

$postFields = [
    'title' => $title,
    'image' => new CURLFile($imagePath, 'image/jpeg', 'image.jpg')
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($data['status'] === 'success') {
    echo "Card generated: " . $data['image_url'];
} else {
    echo "Error: " . $data['message'];
}
```

### 2. JavaScript (Fetch API)

```javascript
async function generatePhotoCard(title, imageFile) {
    const formData = new FormData();
    formData.append('title', title);
    formData.append('image', imageFile);

    try {
        const response = await fetch('/api/photo-card', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if (data.status === 'success') {
            console.log('Card URL:', data.image_url);
            // Use data.image_url to display or download the image
            window.open(data.image_url, '_blank');
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Request failed:', error);
    }
}

// Usage
const titleInput = document.getElementById('title');
const imageInput = document.getElementById('image');

generatePhotoCard(titleInput.value, imageInput.files[0]);
```

### 3. Python (requests)

```python
import requests

url = 'http://localhost:8000/api/photo-card'
title = 'আজকের সংবাদ'
image_path = '/path/to/image.jpg'

with open(image_path, 'rb') as image_file:
    files = {
        'image': image_file
    }
    data = {
        'title': title
    }
    
    response = requests.post(url, files=files, data=data)
    
    if response.status_code == 200:
        result = response.json()
        if result['status'] == 'success':
            print(f"Card URL: {result['image_url']}")
        else:
            print(f"Error: {result['message']}")
    else:
        print(f"HTTP Error: {response.status_code}")
```

### 4. cURL Command Line

```bash
# Generate a card with Bangla title
curl -X POST http://localhost:8000/api/photo-card \
  -F "title=আজকের সংবাদ - আজকের খবর" \
  -F "image=@/path/to/your/image.jpg" \
  -H "Accept: application/json"

# With English title
curl -X POST http://localhost:8000/api/photo-card \
  -F "title=Breaking News: Important Update" \
  -F "image=@/path/to/your/image.jpg" \
  -H "Accept: application/json"
```

### 5. JavaScript (Node.js with Axios)

```javascript
const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');

async function generatePhotoCard(title, imagePath) {
    const formData = new FormData();
    formData.append('title', title);
    formData.append('image', fs.createReadStream(imagePath));

    try {
        const response = await axios.post(
            'http://localhost:8000/api/photo-card',
            formData,
            {
                headers: formData.getHeaders(),
                Accept: 'application/json'
            }
        );

        if (response.data.status === 'success') {
            console.log('Card URL:', response.data.image_url);
            return response.data.image_url;
        }
    } catch (error) {
        console.error('Error:', error.response?.data?.message || error.message);
    }
}

// Usage
generatePhotoCard('আজকের সংবাদ', '/path/to/image.jpg');
```

### 6. PHP (Guzzle HTTP)

```php
<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$client = new Client();
$url = 'http://localhost:8000/api/photo-card';
$title = 'আজকের সংবাদ';
$imagePath = '/path/to/your/image.jpg';

$multipart = [
    [
        'name'     => 'title',
        'contents' => $title
    ],
    [
        'name'     => 'image',
        'contents' => fopen($imagePath, 'r'),
        'filename' => 'image.jpg'
    ]
];

try {
    $response = $client->post($url, [
        'multipart' => $multipart,
        'headers' => [
            'Accept' => 'application/json'
        ]
    ]);

    $data = json_decode($response->getBody()->getContents(), true);
    
    if ($data['status'] === 'success') {
        echo "Card URL: " . $data['image_url'];
    } else {
        echo "Error: " . $data['message'];
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Response Format

### Success Response

```json
{
    "status": "success",
    "image_url": "http://localhost:8000/storage/news-cards/card_1712345678_abc123def.jpg"
}
```

### Error Response

```json
{
    "message": "The title field is required.",
    "errors": {
        "title": ["The title field is required."]
    }
}
```

## Error Codes

| HTTP Status | Description |
|------------|-------------|
| 200 | Success - Card generated successfully |
| 422 | Validation Error - Missing required fields |
| 500 | Server Error - Internal server error |

## Rate Limiting

Currently, there are no rate limits implemented. However, it's recommended to:

- Cache generated cards instead of regenerating them
- Implement your own rate limiting at the application level
- Use appropriate image sizes (recommended: 1920x1080 max)

## Testing the API

### Using Postman

1. Create a new POST request
2. URL: `http://localhost:8000/api/photo-card`
3. Go to "Body" tab
4. Select "form-data"
5. Add fields:
   - `title` (text): Your news title
   - `image` (file): Select an image file
6. Click "Send"

### Using Insomnia

1. Create a new POST request
2. URL: `http://localhost:8000/api/photo-card`
3. Select "Multipart Form" body type
4. Add parameters:
   - `title`: Your news title
   - `image`: Upload file
5. Click "Send"

## Integration Examples

### WordPress Plugin Integration

```php
<?php
// Add to your WordPress theme's functions.php

function generate_news_card($title, $image_id) {
    $image_path = get_attached_file($image_id);
    
    $url = 'http://localhost:8000/api/photo-card';
    
    $response = wp_remote_post($url, [
        'body' => [
            'title' => $title,
            'image' => new CURLFile($image_path)
        ]
    ]);
    
    if (!is_wp_error($response)) {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        return $data['image_url'];
    }
    
    return null;
}
```

### React Component

```jsx
import React, { useState } from 'react';

function PhotoCardGenerator() {
    const [title, setTitle] = useState('');
    const [image, setImage] = useState(null);
    const [result, setResult] = useState(null);
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('title', title);
        formData.append('image', image);

        setLoading(true);
        
        try {
            const response = await fetch('/api/photo-card', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.status === 'success') {
                setResult(data.image_url);
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <input 
                    type="text" 
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    placeholder="Enter title"
                />
                <input 
                    type="file" 
                    onChange={(e) => setImage(e.target.files[0])}
                    accept="image/*"
                />
                <button type="submit" disabled={loading}>
                    {loading ? 'Generating...' : 'Generate Card'}
                </button>
            </form>
            
            {result && (
                <div>
                    <p>Card generated!</p>
                    <a href={result} target="_blank">Download</a>
                </div>
            )}
        </div>
    );
}

export default PhotoCardGenerator;
```

## Best Practices

1. **Image Size**: Keep images under 5MB for optimal performance
2. **Image Format**: Use JPEG for photographs, PNG for graphics
3. **Title Length**: Keep titles under 200 characters for best results
4. **Caching**: Cache generated cards to avoid regeneration
5. **Error Handling**: Always handle API errors gracefully
6. **Security**: Validate input on both client and server side
7. **Storage**: Monitor storage usage and implement cleanup policies

## Support

For API issues or questions:
- Check the main README.md for troubleshooting
- Verify all requirements are met
- Ensure server is running on port 8000
- Check Laravel logs in storage/logs/
