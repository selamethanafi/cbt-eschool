<?php
// Contoh jawaban_benar dari database
$jawaban_benar = "burung:merpati|ayam:jago|ikan:bandeng|daging:sapi";

// Memisahkan string jawaban_benar menjadi array
$jawaban_parts = explode("|", $jawaban_benar);

// Array untuk menyimpan opsi kiri dan kanan
$opsi_kiri = [];
$opsi_kanan = [];

// Mengisi array opsi kiri dan kanan
foreach ($jawaban_parts as $part) {
    list($kiri, $kanan) = explode(":", $part);
    $opsi_kiri[] = $kiri;
    $opsi_kanan[] = $kanan;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Soal Menjodohkan</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            position: relative;
            height: 300px;
        }
        .opsi-kiri, .opsi-kanan {
            display: flex;
            flex-direction: column;
        }
        .opsi {
            margin: 10px;
            padding: 15px;
            width: 80px;
            background-color: lightblue;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }
        .opsi-kanan .opsi {
            background-color: lightgreen;
        }
        .line {
            position: absolute;
            background: black;
            z-index: -1;
        }
        #clearButton, #postButton {
            margin-top: 20px;
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #clearButton { background-color: red; }
        #postButton { background-color: green; }
    </style>
</head>
<body>

<div class="container">
    <div class="opsi-kiri">
        <?php foreach ($opsi_kiri as $kiri): ?>
            <div class="opsi" id="opsi-<?= $kiri ?>" data-id="<?= $kiri ?>"><?= $kiri ?></div>
        <?php endforeach; ?>
    </div>

    <div class="opsi-kanan">
        <?php
        shuffle($opsi_kanan);
        foreach ($opsi_kanan as $kanan): ?>
            <div class="opsi" id="jawaban-<?= $kanan ?>" data-id="<?= $kanan ?>"><?= $kanan ?></div>
        <?php endforeach; ?>
    </div>
</div>

<button id="clearButton">Clear</button>
<button id="postButton">Post</button>

<script>
$(document).ready(function() {
    let leftElement = null;
    let rightElement = null;
    let pairs = [];

    function addClickListeners() {
        $(".opsi-kiri .opsi").off('click').click(function() {
            if (leftElement) leftElement.removeClass("selected");
            leftElement = $(this).addClass("selected");
        });

        $(".opsi-kanan .opsi").off('click').click(function() {
            if (rightElement) rightElement.removeClass("selected");
            rightElement = $(this).addClass("selected");

            if (leftElement && rightElement) {
                if (pairs.some(pair => pair.left === leftElement.attr('id') && pair.right === rightElement.attr('id'))) return;

                createLine(leftElement, rightElement);

                // Simpan data ke .data di garis
                let line = $(".line").last();
                line.data('left', leftElement.attr('id'));
                line.data('right', rightElement.attr('id'));

                pairs.push({ left: leftElement.attr('id'), right: rightElement.attr('id') });

                leftElement.off('click');
                rightElement.off('click');

                leftElement.removeClass("selected");
                rightElement.removeClass("selected");

                leftElement = null;
                rightElement = null;
            }
        });
    }

    function createLine(leftElement, rightElement) {
        const leftPos = leftElement.offset();
        const rightPos = rightElement.offset();
        const deltaX = rightPos.left - leftPos.left;
        const deltaY = rightPos.top - leftPos.top;
        const length = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
        const angle = Math.atan2(deltaY, deltaX) * 180 / Math.PI;

        const line = $("<div>").addClass("line");
        line.css({
            top: leftPos.top + leftElement.outerHeight() / 2,
            left: leftPos.left + leftElement.outerWidth(),
            width: length,
            height: 3,
            transform: "rotate(" + angle + "deg)",
            transformOrigin: "0 0"
        });

        $("body").append(line);
    }

    $("#clearButton").click(function() {
        $(".line").remove();
        $(".opsi-kiri .opsi, .opsi-kanan .opsi").removeClass("selected");
        pairs = [];
        addClickListeners();
        leftElement = null;
        rightElement = null;
    });

    $("#postButton").click(function() {
        let result = [];

        $(".line").each(function() {
            const leftId = $(this).data('left');
            const rightId = $(this).data('right');
            if (leftId && rightId) {
                result.push({
                    left: leftId.replace('opsi-', ''),
                    right: rightId.replace('jawaban-', '')
                });
            }
        });

        let formattedResult = result.map(pair => `${pair.left}:${pair.right}`).join('|');

        $.ajax({
            url: 'simpan_json.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ postData: formattedResult }),
            success: function(response) {
                const jsonResponse = JSON.parse(response);
                alert(jsonResponse.message);
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan: ' + error);
            }
        });
    });

    addClickListeners();
});
</script>

</body>
</html>
