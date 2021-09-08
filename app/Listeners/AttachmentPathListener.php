
<?php
 
namespace ModulePassport\Listeners;
 
use ModulePassport\Events\AttachmentPathEvent;
 
class AttachmentPathListener extends AbstractListener
{
    /**
     * Handle the event.
     *
     * @param  AttachmentPathEvent  $event
     * @return void
     */
    public function handle(AttachmentPathEvent $event)
    {
        info('test');
    }
}
