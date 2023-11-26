<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\ClickupIntegration\Http\Controllers'], function()
{
    Route::group(['prefix' => 'clickup/tasks'], function()
    {
        // Sidebar List and Unlink
        Route::get('linked/{conversationId}', 'ClickupIntegrationController@linkedTasks')->name('clickup.tasks.linked');
        Route::post('link', 'ClickupIntegrationController@linkTask')->name('clickup.tasks.link');
        Route::delete('unlink', 'ClickupIntegrationController@unlinkTask')->name('clickup.tasks.unlink');

        // Modal HTML, Search and Add
        Route::get('link-modal/{conversationId}', 'ClickupIntegrationController@linkTasks')->name('clickup.tasks.link-modal');
    });
});
