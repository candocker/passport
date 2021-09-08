
<?php
 
namespace ModulePassport\Events;
 
use ModulePassport\Models\AttachmentPath;
 
class AttachmentPathEvent extends AbstractEvent
{
    public $attachmentPath;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AttachmentPath $attachmentPath)
    {
        $this->attachmentPath = $attachmentPath;
    }
}
