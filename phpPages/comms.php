<?php
session_start();
$ch_comms = curl_init();
$url_comms = 'http://192.168.1.187/projects/ClassevivaComms/Fat3/noticeboard';
curl_setopt($ch_comms, CURLOPT_URL, $url_comms);
curl_setopt($ch_comms, CURLOPT_POSTFIELDS, http_build_query(array('id'=> $_SESSION['id'], 'token' => $_SESSION['token'])));
curl_setopt($ch_comms, CURLOPT_RETURNTRANSFER, true);
$response_comms = curl_exec($ch_comms);
$commsData = json_decode($response_comms, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabella Dati</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">
<div class="container mx-auto overflow-x-auto">
    <h1 class="text-2xl font-bold mb-4">Tabella Dati</h1>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Titolo</th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Categoria</th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Validità</th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Allegati</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <?php foreach ($commsData['items'] as $item) { ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?php echo $item['cntTitle']; ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?php echo $item['cntCategory']; ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?php echo $item['cntValidFrom']; ?> - <?php echo $item['cntValidTo']; ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <?php if ($item['cntHasAttach']) { ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Allegato
                                </span>
                    <?php } else { ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Nessun Allegato
                                </span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>

</html>


