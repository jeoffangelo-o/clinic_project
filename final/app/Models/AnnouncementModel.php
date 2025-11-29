<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table            = 'announcements';
    protected $primaryKey       = 'announcement_id';
  
    protected $allowedFields    = ['title',
                                    'content',
                                    'posted_by',
                                    'posted_at',
                                    'posted_until',
                                    'url'
                                    ];

}
