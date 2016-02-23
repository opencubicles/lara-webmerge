<?php
namespace Mindastic\LaraWebmerge;

require_once __DIR__ . '/../vendor/autoload.php';

class WebMerge {

  /**
   * App Key
   * @var string
   */
  private $key = null;

  /**
   * App Secret
   * @var string
   */
  private $secret = null;


  /**
   *
   * @var \GuzzleHttp\Client;
   */
  private $client = null;

  /**
   * API Url
   * @var string
   */
  private $apiUrl = 'https://www.webmerge.me/api/documents';

  /**
   * Initializes a new WebMerge instance with API key and secret
   * @param string $key
   * @param string $secret
   */
  public function __construct($key, $secret) {
    $this->key = $key;
    $this->secret = $secret;
    $this->client = new \GuzzleHttp\Client([
      'base_uri' => $this->apiUrl,
      'auth' => [$this->key, $this->secret, 'digest']
    ]);
  }

  /**
   * Performs an API Request
   * @param string $method
   * @param array $options
   * @param string $url
   * @return Promise\PromiseInterface
   */
  private function doRequest($method = 'GET', $options = [], $url = '/') {
    return  $this->client->request($method, $url, $options);
  }

  /**
   * Creates a Document
   * For more information check <a href='https://www.webmerge.me/developers/documents'>Documents</a>
   * @param string $name
   * @param string $type "html", "pdf", "docx", "xlsx", or "pptx"
   * @param string $output "pdf", "docx", "xlsx", "pptx", or "email"
   * @param string $outputName
   * @param string $outputDir
   * @param string $html
   * @param string $width
   * @param string $height
   * @param string $content
   * @param array $notification
   */
  private function createDocument($name, $type, $output, $outputName = null,
      $outputDir = null, $html = null, $size_width = null, $size_height = null, $content = null,
      $notification = []) {

    $options = [
      'form_params' => [
        'name'    => $name,
        'type'    => $type,
        'output'  => $output
      ]
    ];

    foreach ([
      'outputName', 'outputDir', 'html',
      'size_width', 'size_height',
      'notification'] as $arg) {
      if (!empty($$arg)) {
        $options['form_params'][$arg] = $$arg;
      }
    }

    if ($type !== 'html') {
      $options['form_params']['file_contents'] = base64_encode($content);
    }

    return $this->doRequest('POST', $options);

  }

  public function createHtmlDocument($name, $output, $html, $outputName = null,
      $outputDir = null, $width = null, $height = null, $notification = []) {

    $type = strtolower($type);
    return $this->createDocument($name, 'html', $output,
        $outputName, $outputDir, $html, $width, $height, null, $notification);
  }

  public function createNonHtmlDocument($name, $type, $output, $content, $outputName = null,
      $outputDir = null, $notification = []) {

    $type = strtolower($type);
    if ($type === 'html') {
      throw new \InvalidArgumentException("Type must not be HTML. Use createHtmlDocument instead.");
    }

    return $this->createDocument($name, $type, $output,
        $outputName, $outputDir, null, null, null, $content, $notification);
  }
}
