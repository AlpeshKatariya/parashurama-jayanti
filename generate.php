<?php
if (isset($_POST['username']) && isset($_FILES['userimage'])) {
    $username = htmlspecialchars($_POST['username']);
    $image = $_FILES['userimage'];

    // Image path setup
    $templatePath = 'template/template.jpg'; 
    $outputDir = 'generated/';
    $outputFilename = uniqid() . '.jpg';
    $outputPath = $outputDir . $outputFilename;

    // Load template
    $template = imagecreatefromjpeg($templatePath);

    // Load user image
    $userImageTmp = $image['tmp_name'];
    $userImage = imagecreatefromstring(file_get_contents($userImageTmp));

    // Resize and merge user image
    $userImageResized = imagescale($userImage, 200, 200); // Adjust size as needed
    imagecopy($template, $userImageResized, 50, 50, 0, 0, 200, 200); // Position (x, y)

    // Set text color & font
    $textColor = imagecolorallocate($template, 255, 255, 255); // white
    $fontPath = __DIR__ . '/arial.ttf'; // Make sure arial.ttf exists here
    $fontSize = 20;

    // Add user name
    imagettftext($template, $fontSize, 0, 300, 100, $textColor, $fontPath, $username);

    // Save final image
    imagejpeg($template, $outputPath);

    // Free memory
    imagedestroy($template);
    imagedestroy($userImageResized);
    imagedestroy($userImage);

    // WhatsApp Share Link
    $fullUrl = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . $outputPath;
    $waText = urlencode("Check out my Parashurama Jayanti post! 🙏\n$fullUrl");
    $waLink = "https://wa.me/?text=$waText";

    echo "<h2>Your Festival Post is Ready!</h2>";
    echo "<img src='$outputPath' width='400'><br><br>";
    echo "<a href='$waLink' target='_blank'>Share on WhatsApp</a>";
} else {
    echo "Please submit the form correctly.";
}
?>
