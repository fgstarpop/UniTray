<?php

namespace App\Http\Controllers\Shop;

use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Banner\Models\Banner;
use App\Domain\LogSearch\Models\LogSearch;
use App\Domain\Page\Models\Page;
use App\Domain\Post\Models\Post;
use App\Domain\Story\Models\Story;
use App\Domain\Type\Models\Type;
use App\Enums\BannerSection;
use App\Enums\BannerState;
use App\Enums\PostState;
use App\Enums\StoryType;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysqli;
use Spatie\SchemaOrg\Schema;

use App\Domain\Activity\Readed;
use Carbon\Carbon;
use App\Domain\Chapter\Models\Chapter;
use Illuminate\Support\Arr;
use App\Domain\Admin\Models\Wallet;
use App\Traits\TuLuyenCfg;
use App\TuLuyen\Model_charater;
use Auth;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//        $this->middleware('auth:web');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$tomorrow = new Carbon('tomorrow midnight');
		$today = new Carbon('today midnight');
		// $readedBook = Readed::where('created_at', '<', $tomorrow)->where('created_at', '>', $today)->get()->unique('stories_id');
		// if (count($readedBook) < 7) {
		//     $readedBook = Readed::orderBy('created_at', 'desc')->get()->unique('stories_id');
		// }
		// if (count($readedBook) < 7) {
		//     $readedBook = Story::select('stories.id as stories_id')->get();
		// }
		// $list = [];
		// foreach ($readedBook as $val) {
		//     array_push($list, $val->stories_id);
		// }
		// $homeSchemaMarkup = $this->schemaMarkup();

		// $slugs = ['do-thi', 'vo-hiep-tien-hiep', 'lich-su-quan-su', 'dong-nhan', 'linh-di', 'vong-du-canh-ky'];

		// $storyQuery = Story::join('story_categories', 'story_categories.stories_id', 'stories.id')
		//     ->join('categories', 'categories.id', 'story_categories.categories_id')
		//     ->where('categories.slug', 'huyen-huyen')
		//     ->select('stories.id', 'stories.avatar', 'stories.name', 'stories.type', 'stories.is_vip', 'stories.origin', 'categories.slug')
		//     ->limit(12)
		//     ->orderByDesc('view_day');
		// foreach ($slugs as $slug) {
		//     $q1 = Story::join('story_categories', 'story_categories.stories_id', 'stories.id')
		//         ->join('categories', 'categories.id', 'story_categories.categories_id')
		//         ->where('categories.slug', $slug)
		//         ->select('stories.id', 'stories.avatar', 'stories.name', 'stories.type', 'stories.is_vip', 'stories.origin', 'categories.slug')
		//         ->limit(12)
		//         ->orderByDesc('view_day');
		//     $storyQuery = $storyQuery->union($q1);
		// }
		// $story = Cache::remember('story', 900, function () use ($storyQuery) {
		//     return $storyQuery->with('media')->get();
		// });

		$storyVip = Cache::remember('storyVip', 900, function () {
			return Story::where('is_vip', '1')->where('complete_free', '1')->orderBy('view_day', 'desc')->take(24)->get();
		});

		$storyVipNew = Cache::remember('storyVipNew', 900, function () {
            return Story::where('is_vip', '1')->where('complete_free', '1')->orderBy('chapter_updated', 'desc')->take(24)->get();
		});

        $storyNewChap = Cache::remember('storyNewChap', 900, function () {
            return Story::where('complete_free', '1')->orderBy('chapter_updated', 'desc')->take(12)->get();
		});

		$storyUpdated = Cache::remember('storyUpdated', 900, function () {
			return Story::orderBy('updated_at', 'DESC')->with(['user', 'media'])->limit(12)->get();
		});

		$storyrandomdaerty = Cache::remember('Storyrandomdaerty', 900, function () {
			$count = Story::count();
			$random = rand(1, $count - 30);
			return Story::where('count_chapters', '>=', '100')->with(['user', 'media'])->skip($random)->limit(20)->get();
		});
		$story_week = Cache::remember('story_week', 900, function () {
			return Story::orderBy('view_week', 'DESC')->where('count_chapters', '>=', '50')->with(['user', 'media'])->limit(12)->get();
		});
        $story_nomination = Cache::remember('story_nomination', 900, function () {
			return Story::where('nomination', '>', '0')->orderBy('nomination', 'DESC')->take(12)->get();
		});
		$storyNews = Cache::remember('storyNews', 900, function () {
			return Story::orderBy('created_at', 'DESC')->visible()->with(['user', 'media'])->limit(20)->get();
		});

		$storyFirst = Cache::remember('storyFirst', 900, function () use ($tomorrow, $today) {
			return Story::latest()->where('created_at', '<', $tomorrow)->where('created_at', '>', $today)->whereColumn('created_at', 'updated_at')->take(12)->get();
		});

		// $storyHuyenHuyen = Cache::remember('storyHuyenHuyen', 900, function () use ($readedBook, $story) {
		//     return $this->readed('huyen-huyen', $readedBook, $story);
		// });

		// $storyDoThi = Cache::remember('storyDoThi', 900, function () use ($readedBook, $story) {
		//     return $this->readed('do-thi', $readedBook, $story);
		// });

		// $storyTienVoHiep = Cache::remember('storyTienVoHiep', 900, function () use ($readedBook, $story) {
		//     return $this->readed('vo-hiep-tien-hiep', $readedBook, $story);
		// });

		// $storyLichSu = Cache::remember('storyLichSu', 900, function () use ($readedBook, $story) {
		//     return $this->readed('lich-su-quan-su', $readedBook, $story);
		// });

		// $storyDongNhan = Cache::remember('storyDongNhan', 900, function () use ($readedBook, $story) {
		//     return $this->readed('dong-nhan', $readedBook, $story);
		// });

		// $storyLinhDi = Cache::remember('storyLinhDi', 900, function () use ($readedBook, $story) {
		//     return $this->readed('linh-di', $readedBook, $story);
		// });

		// $storyVongDu = Cache::remember('storyVongDu', 900, function () use ($readedBook, $story) {
		//     return $this->readed('vong-du-canh-ky', $readedBook, $story);
		// });

		$storyEmbeds = Cache::remember('storyEmbeds', 900, function () {
			return Story::where('type', StoryType::EMBED)->with('media')->visible()->latest()->take(12)->get();
		});

		$storyWrittens = $storyRandoms = Cache::remember('storyWrittens', 900, function () {
			return Story::where('type', StoryType::WRITTEN)->visible()->latest()->take(12)->get();
		});

		$bannerMains = Cache::remember('bannerMains', 900, function () {
			return Banner::where('section', BannerSection::Slide)->where('status', BannerState::Active)->with('media')->orderBy('position', 'desc')->get();
		});

		$requireVip = Cache::remember('requireVip', 900, function () {
			return  Story::whereNull('mod_id')->orderBy('donate', 'desc')->with(['user', 'media'])->take(30)->get();
		});

		$top_level = Cache::remember('top_level_max', 600, function () {
			$top_level = Model_charater::with('get_users')->where('is_online', 1)
			->orderBy('level', 'desc')->orderByDesc('exp')
			->take(20)->get();


			$tuluyen = new TuLuyenCfg();
			foreach ($top_level as $key => $value) {
				$top_level[$key]['lv_name'] = $tuluyen->get_lv_name($value->level, $value->class);
			}
			return $top_level;
		});

		$user = Cache::remember('user', 900, function () {
			return   User::orderBy('created_at', 'desc')->where('status', \App\Enums\UserState::Active)->take(10)->get();
		});
        $storyReaded = [];
        if (auth()->guard('web')->user()) {
            $readRecently =  Readed::where('user_id', currentUser()->id)
                ->orderBy('updated_at', 'desc')
                ->with('stories')
                ->limit(20)
                ->get();
            foreach ($readRecently as $item) {
                if (!empty($item->stories)) {
                    $story = new \stdClass();
                    $story->id = $item->stories->id;
                    $story->name = $item->stories->name;
                    $story->avatar = $item->stories->avatar ?? $item->stories->getFirstMediaUrl('default');
                    $story->count_chapters = $item->stories->count_chapters;
                    $storyReaded[] = $story;
                }
            }
        }
        $userRanking = [];
        //Ranking user dedication
        $userDedicationIds = [];
        $walletTransaction = Cache::remember('walletTransaction', 900, function () {
            if (Carbon::now()->day >= 28) {
                return WalletTransaction::whereBetween('created_at', [
                    Carbon::now()->startOfMonth()->format('Y-m-28'),
                    Carbon::now()
                ])->where([
                    'change_type' => 0,
                    'transaction_type' => 0
                ])->select('user_id', DB::raw('sum(gold) as total'))
                    ->groupBY('user_id')
                    ->orderByRaw('SUM(gold) DESC')
                    ->limit(20)
                    ->get();
            } else {
                return WalletTransaction::whereBetween('created_at', [
                    Carbon::now()->startOfMonth()->subMonth()->format('Y-m-28'),
                    Carbon::now()
                ])->where([
                    'change_type' => 0,
                    'transaction_type' => 0
                ])->select('user_id', DB::raw('sum(gold) as total'))
                    ->groupBY('user_id')
                    ->orderByRaw('SUM(gold) DESC')
                    ->limit(20)
                    ->get();
            }
        });
        foreach ($walletTransaction as $item) {
            $userDedicationIds[] = $item->user_id;
        }
        $userRanking['dedication'] = User::whereIn('id', $userDedicationIds)->get()->toArray();
        foreach ($userRanking['dedication'] as &$user) {
            foreach ($walletTransaction->toArray() as $wallet) {
                if ($user['id'] == $wallet['user_id']) {
                    $user['text'] = 'Đã nạp ' . number_format($wallet['total']);
                    $user['value'] = $wallet['total'];
                    break;
                }
            }
        }
        $userRanking['dedication'] = $this->sortArrayHasOrder($userRanking['dedication'], 'value', true);

        //Ranking user gold
        $userRanking['gold'] = [];

        //Ranking user master
        $userRanking['master'] = [];

        //Ranking user training
        $userRanking['training'] = Cache::remember('playersExp', 900, function () {
            return  User::select('users.*', 'p.level', 'p.exp', 'p.user_id')
                ->join('players_charater as p', 'users.id', 'p.user_id')
                ->orderBy('p.level', 'DESC')
                ->orderBy('p.exp', 'DESC')
                ->limit(20)
                ->get()->toArray();
        });
        foreach ($userRanking['training'] as &$user) {
            $user['text'] = 'Cấp: ' . number_format($user['level']);
            $user['value'] = $user['level'];
        }

        //Ranking user atk
        $playersAtk = Cache::remember('playersAtk', 900, function () {
            return Model_charater::select('user_id', DB::raw('(atk + sum_atk) as total_atk'))
                ->orderByRaw('total_atk DESC')
                ->limit(20)
                ->get();
        });
        $userHighLevel = [];
        foreach ($playersAtk as $player) {
            $userHighLevel[] = $player->user_id;
        }
        $userRanking['atk'] = User::whereIn('id', $userHighLevel)->get()->toArray();;
        foreach ($userRanking['atk'] as &$user) {
            foreach ($playersAtk->toArray() as $player) {
                if ($user['id'] == $player['user_id']) {
                    $user['text'] = 'Sức mạnh ' . number_format($player['total_atk']);
                    $user['value'] = (int)$player['total_atk'];
                    break;
                }
            }
        }
        $userRanking['atk'] = $this->sortArrayHasOrder($userRanking['atk'], 'value', true);

        //Ranking user def
        $playersDef = Cache::remember('playersDef', 900, function () {
            return Model_charater::select('user_id', DB::raw('(def + sum_def) as total_def'))
                ->orderByRaw('total_def DESC')
                ->limit(20)
                ->get();
        });

        $userHighLevel = [];
        foreach ($playersDef as $player) {
            $userHighLevel[] = $player->user_id;
        }

        $userRanking['def'] = User::whereIn('id', $userHighLevel)->get()->toArray();;
        foreach ($userRanking['def'] as &$user) {
            foreach ($playersDef->toArray() as $player) {
                if ($user['id'] == $player['user_id']) {
                    $user['text'] = 'Phòng thủ ' . number_format($player['total_def']);
                    $user['value'] = (int)$player['total_def'];
                    break;
                }
            }
        }
        $userRanking['def'] = $this->sortArrayHasOrder($userRanking['def'], 'value', true);

        return view('shop.home', compact(
			'story_week',
            'storyNewChap',
			'storyrandomdaerty',
			'user',
			'storyEmbeds',
			'storyWrittens',
			'storyFirst',
			'storyUpdated',
			'storyVip',
			'storyVipNew',
			'storyNews',
			'storyRandoms',
			'bannerMains',
			'requireVip',
			'top_level',
            'storyReaded',
            'userRanking',
            'story_nomination'
		));
	}

	public function readed($category, $readedBook = null, $story)
	{
		$list = [];
		foreach ($readedBook as $val) {
			array_push($list, $val->stories_id);
		}

		$story = $story->where('slug', $category);

		$storyInList = $story->whereIn('id', $list);
		if (count($storyInList) >= 7) {
			$story = $storyInList->take(12);
		}
		return $story->take(12);
	}

	public function schemaMarkup()
	{
		return Schema::organization()
			->url(config('app.url'))
			->contactPoint(
				Schema::contactPoint()
					->name(setting('store_name', null))
					->description(setting('store_description', null))
					->telephone(setting('store_phone', null))
					->email(setting('store_email', null))
					->image(Schema::imageObject()
						->url(\Storage::url(setting('store_logo')))
						->width('60')
						->height('60'))
			);
	}
	public function editname()
	{

		$servername = env('DB_HOST', '127.0.0.1');
		$username   = env('DB_USERNAME', 'forge');
		$password   = env('DB_PASSWORD', '');
		$dbname     = env('DB_DATABASE', 'forge');
		$port       = env('DB_PORT', '3126');

		$conn = new mysqli($servername, $username, $password, $dbname, $port);

		switch ($_REQUEST['ajax']) {
			case "mynamepack": //get tất cả gói name của user hiện tại
				if (!currentUser()) {
					die("Vui Lòng Đăng Nhập");
				}

				$id =  currentUser()->id;      // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong request
				$query = 'SELECT * FROM packagename WHERE user_id = ? ';

				$sql = $conn->prepare($query);
				$sql->bind_param('i', $id);
				$sql->execute();
				$result = $sql->get_result();
				$sql->close();
				while ($tempRow = $result->fetch_assoc()) {
					if ($tempRow['tag'] != "") {
						$taglist = explode(",", $tempRow['tag']);
						$tags = "<span class='tag'>" . implode("</span> <span class='tag'>", $taglist) . "</span>";
					} else $tags = "";

					echo " <div class='roww'>
                        <div class='namepack'>
                            <span class='name'>$tempRow[name]</span> $tags<br>
                            $tempRow[description] - $tempRow[download] tải - " . strlen($tempRow['content']) .
						" chữ - $tempRow[user_id]
                        </div>
                        <div class='padder'></div>
                        <button onclick='downthisname($tempRow[id])'><i class='fas fa-download'></i></button>
                    </div>";
				}
				break;
			case "downcustomname": //tải gói name bất kỳ của người khác xuống.
				if (!isset($_REQUEST['packid'])) {
					die("Lỗi");
				}
				$id = $_REQUEST['packid'];
				$query = "SELECT id,content FROM packagename WHERE id= ?";
				$sql = $conn->prepare($query);
				$sql->bind_param('i', $id);
				$sql->execute();
				$result = $sql->get_result();
				$sql->close();
				if ($result->num_rows > 0) {
					$tempRow = $result->fetch_assoc();
					echo  $tempRow['content'];
					$conn->query("UPDATE packagename SET download=download+1 WHERE id= $tempRow[id]");
				} else {
					die("error");
				}
				break;
			case "makenewnamepack": //upload gói name mới lên server, json encode từ js
				if (!currentUser()) {
					die("Không đăng nhập không thể upload gói name");
				}

				$id =  currentUser()->id;      // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong request
				if (!isset($_REQUEST['data'])) {
					die("Lỗi");
				}
				$data = json_decode($_REQUEST['data'], true); //giải mã json xử lý
				if ($data == null) {
					die("Lỗi không xác định.");
				}
				$name = $data['name'];
				$desc = $data['desc'];
				$tag = $data['tag'];
				$content = $data['data'];
				$user_id = $id; // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong json
				if (strlen($name) < 6 || strlen($name) > 50) {
					die("Tên quá ngắn hoặc quá dài");
				}
				if (strlen($content) < 100) {
					die("Gói name quá ngắn.");
				}
				if (strlen($desc) > 60) {
					die("Giới thiệu quá dài");
				}
				$query = "INSERT INTO packagename(name,user_id,content,tag,description)  VALUES(?,?,?,?,?)";
				$sql = $conn->prepare($query);
				$sql->bind_param('sisss', $name, $user_id, $content, $tag, $desc);
				$sql->execute();
				$sql->close();
				echo "success";
				break;
			case "editnamepack": //download gói name của chính mình để edit
				if (!isset($_REQUEST['id'])) {
					die("Lỗi");
				}
				if (!currentUser()) {
					die("Không đăng nhập không thể tải gói name về edit");
				}


				$user_id = currentUser()->id;  // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong request
				$id = $_REQUEST['id'];
				$query = 'SELECT * FROM packagename WHERE user_id = ? and id = ?';
				$sql = $conn->prepare($query);
				$sql->bind_param('ii', $user_id, $id);
				$sql->execute();
				$result = $sql->get_result();
				$sql->close();
				if ($result->num_rows > 0) {
					$tempRow = $result->fetch_assoc();
					$obj = new stdClass();
					$obj->code = 0;
					$obj->name = $tempRow['name'];
					$obj->desc = $tempRow['description'];
					$obj->tag = $tempRow['tag'];
					$obj->data = $tempRow['content'];
					echo json_encode($obj);
				} else {
					die('{"error":"Không phải chủ gói name","code":1}');
				}
				break;
			case "editnamepacked"; //upload gói name chỉnh sửa của mình lên server,json encode từ js
				if (!currentUser()) {
					die("Không đăng nhập không thể upload gói name edit");
				}
				if (!isset($_REQUEST['data'])) {
					die("Lỗi");
				}
				$data = json_decode($_REQUEST['data'], true); //giải mã json xử lý
				if ($data == null) {
					die("Lỗi");
				}
				$id = $data['id'];
				$name = $data['name'];
				$desc = $data['desc'];
				$tag = $data['tag'];
				$content = $data['data'];
				$user_id = currentUser()->id;  // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong json
				if (strlen($name) < 6 || strlen($name) > 50) {
					die("Tên quá ngắn hoặc quá dài");
				}
				if (strlen($content) < 100) {
					die("Gói name quá ngắn.");
				}
				if (strlen($desc) > 60) {
					die("Giới thiệu quá dài");
				}
				$query = 'SELECT * FROM packagename WHERE user_id = ? and id = ?';
				$sql = $conn->prepare($query);
				$sql->bind_param('ii', $user_id, $id);
				$sql->execute();
				$result = $sql->get_result();
				$sql->close();
				if ($result->num_rows > 0) {
					$query = "UPDATE packagename SET name = ?,content = ?,tag = ?,description = ? WHERE id = ? and user_id= ?";
					$sql2 = $conn->prepare($query);
					$sql2->bind_param('ssssii', $name, $content, $tag, $desc, $user_id, $id);
					$sql2->execute();
					$sql2->close();
					echo "success";
				} else {
					die('{"error":"Không phải chủ gói name không thể edit","code":1}');
				}

				break;
			case "searchnamepack"; //tìm kiếm và load gói name
				if (!isset($_REQUEST['keyw'])) {
					die("Lỗi");
				}
				$key = "%{$_REQUEST['keyw']}%";

				$query = "SELECT * FROM packagename WHERE name LIKE ? OR tag LIKE ? ORDER BY download DESC LIMIT 20";
				$sql = $conn->prepare($query);
				$sql->bind_param('ss', $key, $key);
				$sql->execute();
				$result = $sql->get_result();
				$sql->close();

				while ($tempRow = $result->fetch_assoc()) {
					if ($tempRow['tag'] != "") {
						$taglist = explode(",", $tempRow['tag']);
						$tags = "<span class='tag'>" . implode("</span> <span class='tag'>", $taglist) . "</span>";
					} else $tags = "";

					echo " <div class='roww'>
                            <div class='namepack'>
                                <span class='name'>$tempRow[name]</span> $tags<br>
                                $tempRow[description] - $tempRow[download] tải - " . strlen($tempRow['content']) .
						" chữ - $tempRow[user_id]
                            </div>
                            <div class='padder'></div>
                            <button onclick='downthisname($tempRow[id])'><i class='fas fa-download'></i></button>
                        </div>";
				}

				break;
			case "namesys":
				if (!isset($_REQUEST['book']) || !isset($_REQUEST['host']))
					die("lỗi");

				$book_id =  $_REQUEST['book'];
				$host =   $_REQUEST['host'];
				$query = "select * from uploadedname where host = ? and book_id = ? limit 20";
				$sql = $conn->prepare($query);
				$sql->bind_param('si', $host, $book_id);
				$sql->execute();
				$result = $sql->get_result();
				$sql->close();
				if ($result->num_rows > 0) {
					while ($temprow = $result->fetch_assoc()) {
						echo  "<tr style='font-size:12px;'><td>$temprow[user_id]</td><td >
                <div style='white-space:nowrap;overflow:hidden;max-width:140px;max-height:26px;font-size:12px;'>$temprow[data]</div>
                </td><td>$temprow[length]</td><td></td>
                <td>
                    <span style='display:inline-block;' onclick='dlName(this);'><i class=\"fas fa-download\"></i></span>
                </td>
                </tr>";
					}
				}
				break;
			case "upload":
				if (!currentUser()) {
					die("Không đăng nhập không thể tải gói name về edit");
				}
				if (!isset($_REQUEST['data']) || !isset($_REQUEST['bookid']) || !isset($_REQUEST['host']))
					die("Lỗi");
				$data = $_REQUEST['data'];
				$data = urldecode($data);
				if (strlen($data) < 100) {
					die("gói name quá ngắn");
				}
				$host = $_REQUEST['host'];
				$bookid = $_REQUEST['bookid'];
				$username = currentUser()->id;		 // có thể dùng như tên gói
				$query = "INSERT INTO uploadedname (host,book_id,user_id,data,length) VALUES(?,?,?,?," . strlen($data) . ")";
				$sql = $conn->prepare($query);
				$sql->bind_param('siis', $host, $bookid, $username, $data);
				$sql->execute();
				if ($sql->affected_rows > 0) {
					$sql->close();
					die(" success");
				} else {
					$sql->close();
					die("upload name không thành công");
				}
				break;
			case "trans":
				if (!isset($_REQUEST['content']))
					die("Lỗi");
				$content = $_REQUEST['content'];
				echo _vp_viet($content);
				break;
			case "worddictss":
				if (!isset($_REQUEST['content']))
					die("Lỗi");
				$content = $_REQUEST['content'];
				echo _vp_viet($content);
				break;
			case "getnamefromdb":
				if (!isset($_REQUEST['name'])) {
					die("Lỗi");
				}
				$name = $_REQUEST['name'];

				$query = 'SELECT * FROM namedbs WHERE BINARY name_t = ?';
				$sql = $conn->prepare($query);
				$sql->bind_param('s', $name);
				$sql->execute();
				$result = $sql->get_result();
				$sql->close();
				if ($result->num_rows > 0) {
					$temprow = $result->fetch_assoc();
					die($temprow['name_v']);
				} else {
					die('');
				}
				break;
			default: //mặc định
				break;
		}
		$conn->close();
	}

    private function sortArrayHasOrder($array = [], $column, $second_soft = false)
    {
        $result = $this->array_sort($array, $column, SORT_DESC);

        if ($second_soft) {
            return $result;
        }
        $price = array();
        foreach ($result as $key => $row)
        {
            $price[$key] = $row[$column];
        }
        array_multisort($result, SORT_DESC, $price);
        return $result;
    }

    private function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = [];
        $sortable_array = [];

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[] = $array[$k];
            }
        }

        return $new_array;
    }
}
