<?php

namespace Ijdb\Controllers;

use Framework\DatabaseTable;

class Category
{

    private $categoryTable;

    public function __construct(DatabaseTable $categoryTable)
    {
        $this->categoryTable = $categoryTable;
    }

    /**
     * Handles category creation or existing category editing
     * 
     * 
     * @param ?string $id of category in DB's `category` table
     */

    public function edit(?string $id = null): array
    {

        if (isset($id)) {
            $category = $this->categoryTable->find('id', $id);
        }

        return [
            'template' => 'editcategory.html.php',
            'title' => 'Enter Joke Category',
            'variables' => [
                'category' => $category ?? null
            ]
        ];
    }

    /**
     * Saves submitted category to DB's `category` table
     * 
     * 
     * After that redirects to /category/list
     */

    public function editSubmit()
    {
        $category = $_POST['category'];
        $this->categoryTable->save($category);

        header('location: /category/list');
    }
}
