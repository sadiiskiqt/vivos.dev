<?php

namespace Module\Accommodations\Controllers;

/*
 * Controller: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;
use Module\Accommodations\Models\Repositories\AccommodationsFilterRepository as AccommodationsFilterRepository;
use Illuminate\Http\Request;
use Module\Accommodations\Models\Accommodations;


class AccommodationsController extends Controller
{

    use \Module\Accommodations\Traits\AccommodationsTrait;

    protected $oAccommodationsFilterRepository;

    public function __construct(AccommodationsFilterRepository $oAccommodationsFilterRepository)
    {
        $this->oAccommodationsFilterRepository = $oAccommodationsFilterRepository;


    }

    public function getIndex()
    {
//        \Atlantis\Helpers\Assets::registerScript('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js', 10);
//        \Atlantis\Helpers\Assets::registerScript('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css', 10);
//        \Atlantis\Helpers\Assets::registerScript('https://code.jquery.com/jquery-1.12.4.js', 10);
//        \Atlantis\Helpers\Assets::registerScript('https://code.jquery.com/ui/1.12.1/jquery-ui.js', 10);


        $aData['aCheckboxFilters'] = $this->getAllCheckboxFilter();
        $aData['aDropDownFilter'] = $this->getAllDropDownFilter();

        return \View::make('accommodations::blank', $aData);
    }

    /**
     * @return array
     */
    public function getAllCheckboxFilter()
    {
        $oCheckbox = $this->oAccommodationsFilterRepository->getAllCheckboxFilters();
        return $oCheckbox;
    }

    /**
     * @return array|null
     */
    public function getAllDropDownFilter()
    {
        $aDropDownFilter = $this->oAccommodationsFilterRepository->getAllDropDownFilters();
        return $aDropDownFilter;
    }

    /**
     * <div data-pattern-func="Module:Accommodations@build"></div>
     */
    public function build()
    {

        //Get rooms per page pagination --- Start ---
        if (!empty($_GET['selected_page'])) {
            foreach ($_GET['selected_page'] as $iPerPage) {
                $aData['aAllRooms'] = $this->oAccommodationsFilterRepository->getAllRooms($iPerPage);
            }
            return \View::make('accommodations::accomodationsRoomsPagination', $aData);
        } else {
            $id = 5;
            $aData['aAllRooms'] = $this->oAccommodationsFilterRepository->getAllRooms($id);
        }
        //---------- End ------------

        //Get room by search field
        if (!empty($_GET['search'])) {
            $aData['aAllRooms'] = $this->oAccommodationsFilterRepository->getRoomsByAutocomplete($_GET['search']);
        }

        //Get all the filters
        $aData['aCheckboxFilters'] = $this->getAllCheckboxFilter();
        $aData['aDropDownFilter'] = $this->getAllDropDownFilter();

        //Get the path for the css,ja, folders and styles
        $pathVendor = config('atlantis.modules_dir') . config('accommodations.setup.path') . '/Module/Accommodations/Vendor/';
        $aData['pathVendor'] = $pathVendor;
//        return [$pathVendor . '/cke/ckeditor.js'];

        return view('accommodations::accommodations', $aData);
    }

    /**
     * @return mixed
     * This method get all the rooms by the filters ( Drop Down and Checkbox )
     */
    public function getRooms()
    {
        $aData = array();
        $aFilteredRooms = $this->oAccommodationsFilterRepository->getRoom($_GET);

        if ($aFilteredRooms != false) {
            $aData['aFilteredRooms'] = $aFilteredRooms;
        } else {
            $aData['aFilteredRooms'] = false;
        }

        return \View::make('accommodations::accomodationsRooms', $aData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request)
    {
        $term = $request->term;

        $data = Accommodations::where('room_title', 'LIKE', '%' . $term . '%')->get();

        $results = array();
        foreach ($data as $key => $val) {
            $results[] = ['id' => $val->id, 'value' => $val->room_title];
        }
        return response()->json($results);
    }
}
