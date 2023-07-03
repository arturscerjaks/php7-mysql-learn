<?php if (empty($joke->id) || $userId == $joke->authorid) : ?>
    <form action="" method="post">
        <input type="hidden" name="joke[id]" value="<?= $joke->id ?? '' ?>">
        <label for="joketext">Type your joke here:</label>
        <textarea id="joketext" name="joke[joketext]" rows="3" cols="40"><?= htmlspecialchars($joke->joketext ?? '', ENT_QUOTES, 'UTF-8')?></textarea>
        <input type="submit" name="submit" value="Save">
    </form>
<?php else : ?>
    <p>You may only edit jokes that you posted.</p>
<?php endif; ?>