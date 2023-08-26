<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->references('id')->on('teachers')->cascadeOnDelete();
            $table->string('title');
            $table->string('description');
            $table->timestamps();
            // $table->foreignId('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });
     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
};


UserFriendRequest      


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserFriendRequest extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'friend_id', 'status'];

    protected $table = 'user_friend_requests';
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_friend_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('friend_id')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('status',['pending','accepted','rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_friend_requests');
    }
};





<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('friend_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
        //userfriends
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};





<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\UserFriendRequest;
use App\Models\User;
use App\Notifications\FriendRequestSent;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    public function send (Request $request)
    {
        $request->validate([
            'friend_id' => ['required', "exists:users,id"],
        ]);
        $friend = User::findOrFail($request->friend_id);
        $friendRequest  =  UserFriendRequest::create([
            'friend_id' =>$friend->friend_id,
            'sender_id' =>auth()->user()->id,
            'status' => 'pending',
        ]);
        $friend->notify(new FriendRequestSent($friendRequest,'pending'));
        return ApiResponse::send('200','Friend request sent' ,NULL);
    }

}
