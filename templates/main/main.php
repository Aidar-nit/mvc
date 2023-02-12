<?php include __DIR__ . '/../header.php'; ?>

<main class="container my-5">
    <div class="container">
        <?php foreach ($articles as $article): ?>
        <div class="article-list">
          <div class="article">
            <h1 class="article-title"><?= $article->getName() ?></h1>
            <p class="article-content"><?= $article->getText() ?> </p>
            <a href="/articles/<?= $article->getId() ?>" class="btn btn-primary btn-sm">Read More</a>
          </div>
        </div>
         <?php endforeach; ?>    
    </div>
</main>
<?php include __DIR__ . '/../footer.php'; ?>
