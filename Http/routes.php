<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\ClickupIntegration\Http\Controllers'], function()
{
    Route::get('/clickup/linked_tasks/{conversationId}', 'ClickupIntegrationController@linkedTasks')->name('clickup.linked_tasks');
    Route::get('/clickup/link_tasks/{conversationId}', 'ClickupIntegrationController@linkTasks')->name('clickup.link_tasks');
    Route::delete('/clickup/unlink_task', 'ClickupIntegrationController@unlinkTask')->name('clickup.unlink_task');
});
