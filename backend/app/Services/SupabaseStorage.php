<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseStorage
{
  protected $url;
  protected $key;
  protected $bucket;

  public function __construct($bucket = null)
  {
    $this->url = env('SUPABASE_URL') . '/storage/v1/object';
    $this->key = env('SUPABASE_KEY');
    $this->bucket = $bucket ?: env('SUPABASE_BUCKET');
  }

  public function setBucket($bucket)
  {
    $this->bucket = $bucket;
    return $this;
  }

  public function upload($path, $file)
  {
    $fileContent = file_get_contents($file->getRealPath());

    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . $this->key,
      'apikey'        => $this->key,
      'Content-Type'  => $file->getMimeType(),
      'x-upsert'      => 'true',
    ])->send(
      'POST',
      "{$this->url}/{$this->bucket}/{$path}",
      ['body' => $fileContent]
    );

    if (!$response->successful()) {
      throw new \Exception('Supabase upload failed: ' . $response->body());
    }

    return $this->publicUrl($path);
  }

  public function publicUrl($path)
  {
    return env('SUPABASE_URL') . "/storage/v1/object/public/{$this->bucket}/{$path}";
  }

  public function delete($path)
  {
    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . $this->key,
      'apikey' => $this->key
    ])->delete(env('SUPABASE_URL') . "/storage/v1/object/{$this->bucket}/{$path}");

    if (!$response->successful()) {
      throw new \Exception('Supabase delete failed: ' . $response->body());
    }

    return true;
  }
}
