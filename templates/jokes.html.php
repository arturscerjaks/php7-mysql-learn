<p><?= $totalJokes ?> jokes have been submitted to the Internet Joke Database.</p>
<?php foreach ($jokes as $joke) : ?>
    <blockquote class="blockquote">
        <p>
            <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?>
            (by <a href="mailto:<?php
                                echo htmlspecialchars(
                                    $joke['email'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"><?php
                                        echo htmlspecialchars(
                                            $joke['name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?></a>
            on <?php
                $jokedate = new DateTime($joke['jokedate']);
                echo $jokedate->format('jS F Y');
                ?>)
            <a href="/joke/edit&id=<?= $joke['id'] ?>">Edit</a>
        </p>
        <form action="/joke/delete" method="post">
            <input type="hidden" name="id" value="<?= $joke['id'] ?>">
            <input type="submit" value="Delete">
        </form>

    </blockquote>
<?php endforeach; ?>