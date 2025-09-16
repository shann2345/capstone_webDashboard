# Server Configuration for Large File Uploads

## Purpose of This File
This documentation helps system administrators configure the web server and PHP to support 100MB file uploads. While the Laravel application code can handle large files, the server infrastructure needs proper configuration.

## Why You Need This Configuration

### Default Limitations
Most servers have restrictive defaults:
- PHP `upload_max_filesize`: Usually 2MB
- PHP `post_max_size`: Usually 8MB  
- Web server body size limits: Varies

### Without Proper Configuration
Users will experience:
- Upload failures for files > 2MB
- Timeout errors on large uploads
- "Request Entity Too Large" errors
- Silent upload failures

## Required PHP Configuration

### In php.ini (Preferred Method):
```ini
; Maximum file upload size (100MB)
upload_max_filesize = 100M

; Maximum POST data size (should be larger than upload_max_filesize)
post_max_size = 120M

; Maximum execution time for uploads (5 minutes)
max_execution_time = 300

; Maximum input parsing time (5 minutes)
max_input_time = 300

; Memory limit (should accommodate file processing)
memory_limit = 512M

; Maximum number of files that can be uploaded simultaneously
max_file_uploads = 20
```

### Alternative: .htaccess (Apache):
```apache
php_value upload_max_filesize 100M
php_value post_max_size 120M
php_value max_execution_time 300
php_value max_input_time 300
php_value memory_limit 512M
```

## Web Server Configuration

### Nginx:
```nginx
server {
    # Maximum request body size
    client_max_body_size 100M;
    
    # Timeout settings for large uploads
    client_body_timeout 300s;
    client_header_timeout 300s;
}
```

### Apache:
```apache
# In httpd.conf or .htaccess
LimitRequestBody 104857600  # 100MB in bytes
```

## How to Verify Configuration

### Check PHP Settings:
```php
<?php
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post Max Size: " . ini_get('post_max_size') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
?>
```

### Test Upload:
1. Try uploading a file > 2MB
2. Check for timeout errors
3. Monitor server logs for errors

## Troubleshooting

### Common Issues:
1. **File uploads fail silently**: Check `upload_max_filesize`
2. **"Request too large" errors**: Increase `post_max_size` and web server limits
3. **Timeout errors**: Increase `max_execution_time` and `max_input_time`
4. **Out of memory errors**: Increase `memory_limit`

### Development vs Production:
- **Development**: Use .htaccess or local php.ini
- **Production**: Configure server-wide php.ini and web server config
- **Shared Hosting**: Contact hosting provider or use .htaccess

## Security Considerations

### File Type Validation:
- Laravel validates file extensions (handled in MaterialController.php)
- Consider adding virus scanning for production
- Restrict executable file access via web server config

### Storage Security:
```apache
# Prevent direct execution of uploaded files (Apache)
<Directory "/path/to/storage/materials">
    php_flag engine off
    AddType text/plain .php .php3 .phtml .pht
</Directory>
```

## Current Application Status

✅ **Laravel Code**: Configured for 100MB uploads  
✅ **Frontend Validation**: JavaScript checks file size  
⚠️ **Server Config**: Needs manual configuration (this file)  

The application will work with smaller files even without server configuration, but large file uploads require the server settings described above.
