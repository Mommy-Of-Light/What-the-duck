<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .jokes-container {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 6px;
        }

        .jokes-container::-webkit-scrollbar {
            width: 6px;
        }

        .jokes-container::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 10px;
        }

        .joke-item {
            background: linear-gradient(145deg, #1e1e1e, #2a2a2a);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .joke-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        .reveal-btn {
            height: 32px;
        }

        .delivery {
            display: none;
            height: 32px;
            margin-top: 1rem !important;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        /* Background section */
        .random-joke-section {
            min-height: 80vh;
        }

        /* Gradient title */
        .gradient-title {
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Glass card */
        .joke-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .joke-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
        }

        /* Joke text */
        .joke-text {
            font-size: 1.4rem;
            font-weight: 500;
            line-height: 1.6;
        }

        /* Hidden delivery */
        .delivery-text {
            display: none;
            opacity: 0;
            font-size: 1.3rem;
            font-weight: 600;
            margin-top: 15px;
            transition: opacity 0.5s ease;
        }

        /* Gradient button */
        .btn-gradient {
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 114, 255, 0.4);
        }

        /* Reload button style */
        .btn-reload {
            background: transparent;
            border: 2px solid #00c6ff;
            color: #00c6ff;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-reload:hover {
            background: #00c6ff;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 198, 255, 0.4);
        }

        /* Spin animation */
        .spin {
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-dark text-light">
    <?php if ($withMenu) {
        echo $this->fetch('menu.php');
    } ?>
    <?= $content ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
