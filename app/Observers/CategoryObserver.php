<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     *
     * @param Category $category
     * @return void
     * @throws \Exception
     */
    public function created(Category $category): void
    {
        //清空缓存
        forget_cache_category();
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param Category $category
     * @return void
     * @throws \Exception
     */
    public function updated(Category $category): void
    {
        //清空缓存
        forget_cache_category();
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function deleted(Category $category)
    {
        //
    }

    /**
     * Handle the Category "restored" event.
     *
     * @param Category $category
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }
}
