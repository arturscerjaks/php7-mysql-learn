<div class="jokelist">

    <ul class="categories">
        <?php foreach ($categories as $category) : ?>
            <li><a href="/joke/list/<?= $category->id ?>"><?= $category->name ?></a>
            <li>
            <?php endforeach; ?>
    </ul>

    <div class="jokes">
        <p><?= $totalJokes ?> jokes have been submitted to the Internet Joke Database.</p>
        <?php foreach ($jokes as $joke) : ?>
            <blockquote class="blockquote">
                <p>
                    <?= htmlspecialchars($joke->joketext, ENT_QUOTES, 'UTF-8') ?>
                    (by <a href="mailto:<?php
                                        echo htmlspecialchars(
                                            $joke->getAuthor()->email,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"><?php
                                                echo htmlspecialchars(
                                                    $joke->getAuthor()->name,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?></a>
                    on <?php
                        $jokedate = new DateTime($joke->jokedate);
                        echo $jokedate->format('jS F Y');
                        ?>)

                    <?php if (empty($joke) || $userId == $joke->authorid) : ?>
                        <a href="/joke/edit/<?= $joke->id ?>">Edit</a>
                </p>
                <form action="/joke/delete" method="post">
                    <input type="hidden" name="id" value="<?= $joke->id ?>">
                    <input type="submit" value="Delete">
                </form>
            <?php endif; ?>

            </blockquote>
        <?php endforeach; ?>
    </div>
</div>