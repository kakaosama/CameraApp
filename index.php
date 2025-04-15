<?php
// webhook に画像を転送するスクリプト
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $webhookUrl = 'https://discord.com/api/webhooks/1192062408462696518/w_kSg9s3GNGxdgVpep893UxRs-rY19ZYRWHVlbOpSnVRjHu40J9NWyLNBCVC9-Ovz4Bt';

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];

        $postFields = [
            'file' => new CURLFile($fileTmpPath, mime_content_type($fileTmpPath), $fileName),
            'content' => $_POST['content'] ?? '画像'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $webhookUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo json_encode([
            'status' => $httpCode,
            'response' => $response
        ]);
    } else {
        echo json_encode(['error' => 'ファイルがアップロードされていないか、エラーがあります。']);
    }
    exit;
}
?>
