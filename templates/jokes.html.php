 <?php if (isset($error)) : ?>
     <p>
         <?= $error ?>
     </p>
 <?php else : ?>
     <?php foreach ($jokes as $joke) : ?>
         <blockquote>
             <p>
                 <?= htmlspecialchars($joke, ENT_QUOTES, 'UTF-8') ?>
             </p>
         </blockquote>
     <?php endforeach; ?>
 <?php endif; ?>