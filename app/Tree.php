<?php

namespace App;


use App\TreeGen\PreliminaryTreeGen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\AuditingTrait;

class Tree extends Model
{
    protected $table = 'tree';
    public $timestamps = true;
    protected $guarded = ['id'];
    use AuditingTrait;

    public static function hasTournament($request)
    {
        return $request->tournamentSlug != null;
    }

    public static function hasChampionship($request)
    {
        return $request->championshipId != null; // has return false, don't know why
    }

    public static function getTournament($request)
    {
        $tournament = new Tournament;
        if (Tree::hasTournament($request)) {
            $tournamentSlug = $request->tournamentSlug;
            $tournament = Tournament::with(
                'championships.settings',
                'championships.category',
                'championships.tree.user1',
                'championships.tree.user2',
                'championships.tree.user3',
                'championships.tree.user4',
                'championships.tree.user5'

            )->where('slug', $tournamentSlug)->first();
        } elseif (Tree::hasChampionship($request)) {
            $tournament = Tournament::whereHas('championships', function($query) use ($request) {
                return $query->where('id', $request->championshipId);
            })
                ->with(['championships' => function ($query) use ($request) {
                    $query->where('id', '=', $request->championshipId)
                        ->with([
                            'settings',
                            'category',
                            'tree' => function($query) {
                                return $query->with('user1', 'user2', 'user3', 'user4', 'user5');
                            }]);
                }])
                ->first();

        }

        return $tournament;
    }


    /**
     * @param $request
     * @return Collection
     */
    public static function getChampionships($request)
    {

        $championships = new Collection();
        if (Tree::hasChampionship($request)) {
            $championship = Championship::with('settings', 'category')->find($request->championshipId);
            $championships->push($championship);
        } else if (Tree::hasTournament($request)) {

            $tournament = Tournament::with(
                'championships.settings',
                'championships.category',
                'championships.tree.user1',
                'championships.tree.user2',
                'championships.tree.user3',
                'championships.tree.user4',
                'championships.tree.user5'

            )->where('slug', $request->tournamentId)->first();

            $championships = $tournament->championships;
        }
        return $championships;
    }

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }

    public function fights()
    {
        return $this->hasMany(Fight::class, 'tree_id', 'id');
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'c1', 'id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'c2', 'id');
    }

    public function user3()
    {
        return $this->belongsTo(User::class, 'c3', 'id');
    }

    public function user4()
    {
        return $this->belongsTo(User::class, 'c4', 'id');
    }

    public function user5()
    {
        return $this->belongsTo(User::class, 'c5', 'id');
    }


    public static function getGenerationStrategy(Championship $championship)
    {
        $tournament = $championship->tournament;
        switch ($tournament->level_id) {
            case Config::get('constants.ND'):
                return new PreliminaryTreeGen($championship, null);
                break;
            case Config::get('constants.local'):
                return new PreliminaryTreeGen($championship, null);
                break;
            case Config::get('constants.district'):
                return new PreliminaryTreeGen($championship, 'club_id');
                break;
            case Config::get('constants.city'):
                return new PreliminaryTreeGen($championship, 'club_id');
                break;
            case Config::get('constants.state'):
                return new PreliminaryTreeGen($championship, 'club_id');
                break;
            case Config::get('constants.regional'):
                return new PreliminaryTreeGen($championship, 'club_id');
                break;
            case Config::get('constants.national'):
                return new PreliminaryTreeGen($championship, 'association_id');
                break;
            case Config::get('constants.international'):
                return new PreliminaryTreeGen($championship, 'federation_id');
                break;
        }
        return null;

        // get Area number
        // get tournament type, and get criteria to repart
        // repart into areas
        // Shuffle
        // Analyse Number of competitors to repart Byes
        // Store / Print

    }

    public static function generateFights(Collection $tree, $settings)
    {

        // Delete previous fight for this championship

        $arrayTreeId = $tree->map(function ($value, $key) {
            return $value->id;
        })->toArray();
        Fight::destroy($arrayTreeId);

        if ($settings->hasPreliminary) {
            for ($numRound = 1; $numRound <= $settings->preliminaryGroupSize; $numRound++) {

                Fight::saveFightRound($tree, $numRound);
            }
        } elseif ($settings->treeType == config('constants.DIRECT_ELIMINATION')) {
            Fight::saveFightRound($tree); // Always C1 x C2
        } elseif ($settings->treeType == config('constants.ROUND_ROBIN')) {

        }

    }
}