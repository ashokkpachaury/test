<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveTV extends Model
{
    protected $table = 'channels_list';
    protected $appends = ['thumbUrl','mediaId'];

    protected $fillable = ['channel_name', 'channel_thumb'];

    protected $hidden = [
        'channel_cat_id',
        'channel_access',
        'channel_name',
        'channel_slug',
        'channel_description',
        'channel_thumb',
        'channel_url_type',
        'channel_url',
        'channel_url2',
        'channel_url3',
        'seo_title',
        'seo_description',
        'seo_keyword',
        'moderator_id'
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('reselect', function ($query) {
            // add given field to camel case select
            return $query->select(
                '*',
                'channel_cat_id as categoryId',
                'channel_access as channelAccess',
                'channel_name as name',
                'channel_slug as slug',
                'channel_description as description',
                'channel_thumb as thumb',
                'thumbUrl as posterUrl',
                'channel_url_type as urlType',
                'channel_url as url',
                'channel_url2 as url2',
                'channel_url3 as url3',
                'seo_title as seoTitle',
                'seo_description as seoDescription',
                'seo_keyword as seoKeyword',
                'moderator_id as moderatorId',
                'channel_thumb as channel_thumb'
            );
        });
    }


    public static function getLiveTvInfo($id, $field_name)
    {
        $livetv_info = LiveTV::where('status', '1')->where('id', $id)->first();

        if ($livetv_info) {
            return $livetv_info->$field_name;
        } else {
            return '';
        }
    }
    public function getThumbUrlAttribute($value)
    {
        //return asset('upload/source/' . $this->channel_thumb);
        //return asset($this->channel_thumb);
        if($this->channel_thumb !=''){
            $url = $this->channel_thumb;
            if(strpos($this->channel_thumb,'http') || strpos($this->channel_thumb,'www'))
            {
                $url = $this->channel_thumb;
            }
            else{
                $url = asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->channel_thumb));
            } 
        }
        else if(isset($this->thumb) && $this->thumb !=''){
            $url = $this->thumb;
            if(strpos($url,'http') || strpos($url,'www'))
            {
                //$url = $this->channel_thumb;
            }
            else{
                $url = asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->thumb));
            } 
        }
        
        return $url;
    }

    public function getChannelThumbAttribute($value)
    {
        // if ($this->attributes['channel_thumb']) return asset('upload/source/' . $this->attributes['channel_thumb']);
        //return  $this->attributes['thumbUrl'];
        if(isset($this->attributes['channel_thumb']) && $this->attributes['channel_thumb']!=''){
            $url = $this->attributes['channel_thumb'];
            if(strpos($url,'http') || strpos($url,'www'))
            {
                $url = $this->attributes['channel_thumb'];;
            }
            else{
                $url = asset('upload/source/' . str_replace(asset('upload/source/').'/','',$url));
            }
        }
        else if(isset($this->attributes['thumbUrl']) && $this->attributes['thumbUrl']!=''){
            $url = $this->attributes['thumbUrl'];
            if(strpos($url,'http') || strpos($url,'www'))
            {
                $url = $this->attributes['thumbUrl'];
            }
            else{
                $url = asset('upload/source/' . str_replace(asset('upload/source/').'/','',$url));
            }
        }
       
        
        return $url;
    }

    //category
    public function category()
    {
        return $this->belongsTo('App\TvCategory', 'channel_cat_id');
    }

    //getRelatedAttribute
    public function getRelatedAttribute($value)
    {
        return LiveTV::where('status', '1')->where('channel_cat_id', $this->channel_cat_id)->where('id', '!=', $this->id)->orderBy('id', 'DESC')->limit(10)->get();
    }

    public function getmediaIdAttribute($value)
    {
        return   $this->attributes['mediaId'];
    }
}
