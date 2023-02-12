<?php include __DIR__ . '/../header.php'; ?>
    <main class="container my-5">
        <h1><?= $article->getName() ?></h1>
        <p><?= $article->getText() ?></p>
        <p><?= $article->getAuthor()->getNickname() ?></p>   
    </main>
<?php include __DIR__ . '/../footer.php'; ?>