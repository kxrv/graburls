<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grab URLs</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #3366cc;
      margin-bottom: 20px;
    }

    form {
      text-align: center;
    }

    label {
      display: block;
      margin-bottom: 8px;
      color: #3366cc;
    }

    textarea {
      width: calc(100% - 22px);
      height: 200px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
      margin-bottom: 15px;
      resize: vertical;
    }

    input[type="submit"] {
      background-color: #3366cc;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      font-size: 14px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #1a4c8a;
    }

    textarea[readonly] {
      background-color: #f5f5f5;
      cursor: not-allowed;
    }

    .footer {
      text-align: center;
      margin-top: 20px;
      color: #666;
      font-size: 12px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><a href="#">Grab URLs</a></h1>

    <!-- Form HTML untuk input -->
    <form method="post" action="">
      <label for="urls">Enter Homepage URLs (separate by new lines):</label><br>
      <textarea id="urls" name="urls" rows="5" cols="50" placeholder="Example: https://example.com/"></textarea><br>
      <input type="submit" id="submit-btn" value="Grab URLs">
    </form>

    <!-- PHP script untuk menampilkan output -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $urls = $_POST["urls"];
        $homepageURLs = explode("\n", $urls);
        $foundUrls = [];

        foreach ($homepageURLs as $homepage) {
            $homepageContent = @file_get_contents(trim($homepage));

            if ($homepageContent !== false) {
                preg_match_all('/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU', $homepageContent, $matches);
                $foundUrls[$homepage] = $matches[2];
            }
        }

        echo "<h3>URL Grab Results:</h3>";
        echo "<textarea rows='10' cols='50'>";
        foreach ($foundUrls as $homepage => $urls) {
            foreach ($urls as $url) {
                if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                    $parsedUrl = parse_url($homepage);
                    $base = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                    $url = rtrim($base, '/') . '/' . ltrim($url, '/');
                    if (strpos($url, $parsedUrl['host']) !== false && strpos($url, '#') === false) {
                        echo $url . "\n";
                    }
                }
            }
        }
        echo "</textarea>";
    }
    ?>
    
    <!-- Footer -->
    <div class="footer">
      &copy; 2023 Kian.cc
    </div>
  </div>
</body>
</html>
