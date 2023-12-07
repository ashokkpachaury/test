<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Movies;
use App\Movies;
use App\Episodes;
// use App\Movies;





class UserSync extends Model
{

    protected $appends = ['data'];


    public function getdataAttribute($value)
    {

        if ($this->type == 'movie') {

            $movieData = Movies::where('id', $this->type_id)->first();
            return     $movieData;
        } else {
            $episodeData = Episodes::where('id', $this->type_id)->first();
            return $episodeData;
        }
    }

    use HasFactory;
    protected $table = 'users_sync';
    // Define the one-to-one relationship to Movies
    public function movie()

    {
        return $this->hasOne(Movies::class, 'id', 'type_id');
    }



    public function episode()
    {
        return $this->hasOne(Episodes::class, 'id', 'type_id');
    }
}
