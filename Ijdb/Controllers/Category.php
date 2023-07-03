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
     * Saves submitted category to DB's `category` table, redirects to list
     */

    public function editSubmit()
    {
        $category = $_POST['category'];
        $this->categoryTable->save($category);

        header('location: /category/list');
    }

    /**
     * Handles displaying existing joke categories
     */

    public function list()
    {
        return [
            'template' => 'categories.html.php',
            'title' => 'Joke Categories',
            'variables' => [
                'categories' => $this->categoryTable->findAll()
            ]
        ];
    }

    /**
     * Handles deletion of categories, redirects to list
     */

    public function deleteSubmit()
    {
        $this->categoryTable->delete('id', $_POST['id']);
        header('location: /category/list');
    }
}
