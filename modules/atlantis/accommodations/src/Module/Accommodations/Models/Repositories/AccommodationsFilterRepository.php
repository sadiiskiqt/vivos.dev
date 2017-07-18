<?php

namespace Module\Accommodations\Models\Repositories;

use Module\Accommodations\Models\Accommodations;
use Module\Accommodations\Models\AccommodationsDropDown;
use Module\Accommodations\Models\Checkbox;
use Module\Accommodations\Models\DropDownOption;

use Module\Accommodations\Models\CheckboxFilter;
use Module\Accommodations\Models\OptionFilter;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AccommodationsFilterRepository
{

    /**
     *
     *
     * public function add($data) {
     *
     * Accommodations::create($data);
     * }
     */


    private $sCheckboxTable = 'accommodations_checkbox_category';
    private $sOptionTable = 'accommodations_dropdown_options';
    private $sDropDownTable = 'accommodations_dropdown_category';
    private $sCheckboxFilterTable = 'accommodations_checkbox_filter';
    private $sOptionFilterTable = 'accommodations_option_filter';
    private $sAccommodationsTable = 'accommodations';


    /**
     * @return array|null
     */
    public function getAllDropDownFilters()
    {
        $aDropDowns = AccommodationsDropDown::all()->toArray();
        foreach ($aDropDowns as $aDropDown) {
            $aOptions = DropDownOption::where('iDropDownId', '=', $aDropDown['id'])->get()->toArray();
            $aAllDropDown[] = array(
                'id' => $aDropDown['id'],
                'sDropDownTitle' => $aDropDown['sDropDownTitle'],
                'option' => $aOptions
            );
        }
        return (!empty($aAllDropDown)) ? $aAllDropDown : null;
    }

    /**
     * @return array
     */
    public function getAllCheckboxFilters()
    {
        $aCheckbox = Checkbox::all()->toArray();
        return $aCheckbox;
    }

    public function getRoomsByAutocomplete($sRoomName)
    {
        $aRoomsByAutocomplete = array();
        $oAccommodations = Accommodations::paginate(15);
        $aRoom = Accommodations::where('room_title', 'LIKE', '%' . $sRoomName . '%')->get()->toArray();
        foreach ($aRoom as $item) {
            $aRoomsByAutocomplete[] = array(
                'oAccommodations' => $oAccommodations,
                'allRooms' => array(
                    'rooms' => $item,
                    'amenities' => $this->getRoomByFilterId($item['id']),
                )
            );
        }
        return $aRoomsByAutocomplete;
    }

    /**
     * @return array
     */
    public function getAllRooms($id)
    {
        //Pagination
        //Get the rooms and the amenities
        $aAccommodationsPerPage = array();
        $oAccommodations = Accommodations::paginate($id);
        $aAccommodationsData = $oAccommodations->toArray();
        foreach ($aAccommodationsData['data'] as $aData) {
            $aAccommodationsPerPage[] = array(
                'oAccommodations' => $oAccommodations,
                'allRooms' => array(
                    'rooms' => $aData,
                    'amenities' => $this->getRoomByFilterId($aData['id']),
                )
            );
        }
        return $aAccommodationsPerPage;
    }

    /**
     * @param array $aFilters
     * @return array|bool
     */
    public function getRoom($aFilters = array())
    {
        foreach ($aFilters['drop'] as $aFilter) {
            if (!empty($aFilter)) {
                $aOptionFilterIds[] = $aFilter;
            }
        }

        if (!empty($aOptionFilterIds)) {
            foreach ($aOptionFilterIds as $aOptionFilterId) {
                if ($aOptionFilterId) {
                    $filteredRooms = OptionFilter::where('optionId', '=', $aOptionFilterId)->get()->toArray();
                    if (!empty($filteredRooms)) {
                        $aRoomIdsOptionRoomIds = array();
                        foreach ($filteredRooms as $aRoom) {
                            $aRoomIdsOptionRoomIds[] = $aRoom['roomId'];
                        }
                    } else {
                        return false;
                        exit();
                    }
                }
            }
        }


        if (!empty($aFilters['check'])) {
            //------------------ All filters only Start -------------

            foreach ($aFilters['check'] as $aFilter) {
                //Get All Rooms By Checkbox filter !!!
                $filteredRoomsByAmenities = CheckboxFilter::where('checkboxId', '=', $aFilter)->get()->toArray();
            }


            //------------------ Checkbox filter only Start -------------
            $aRoomIdByCheckbox = array();
            if (empty($aOptionFilterIds)) {

            }

            foreach ($filteredRoomsByAmenities as $filteredRoomsByAmenity) {
                $aRoomIdByCheckbox[$filteredRoomsByAmenity['roomId']] = $filteredRoomsByAmenity['roomId'];
            }
            if (!empty($aRoomIdByCheckbox)) {
                //Get all Rooms by filters
                foreach ($aRoomIdByCheckbox as $iRoomId) {
                    $aRoomByFilter[] = array(
                        'accommodations' => Accommodations::where('id', '=', $iRoomId)->get()->toArray(),
                        'amenities' => $this->getRoomByFilterId($iRoomId)
                    );
                }
                if (!empty($aRoomByFilter)) {

                    return $aRoomByFilter;

                } else {
                    return false;
                    exit();
                }
            }
            //------------------ Checkbox filter End -------------


            //------------------ All filters only Start -------------
            $aAllFilters = array();
            if (!empty($aRoomIdsOptionRoomIds)) {
                foreach ($filteredRoomsByAmenities as $filteredRoomsByAmenity) {

                    foreach ($aRoomIdsOptionRoomIds as $aRoomIdsOptionRoomId) {
                        if ($aRoomIdsOptionRoomId == $filteredRoomsByAmenity['roomId']) {
                            $aAllFilters[] = $filteredRoomsByAmenity['roomId'];
                        }
                    }
                }
            }

            //Get Room By all Filters
            $aRoomByAllFilters = array();
            if (!empty($aAllFilters)) {
                foreach ($aAllFilters as $aAllFilter) {
                    $aRoomByAllFilters[] = array(
                        'accommodations' => Accommodations::where('id', '=', $aAllFilter)->get()->toArray(),
                        'amenities' => $this->getRoomByFilterId($aAllFilter)
                    );
                }

                return $aRoomByAllFilters;
            } else {
                return false;
                exit();
            }

            //------------------ All filters only End -------------

        } else {
            //------------------ Drop Down filter only Start -------------

            foreach ($aRoomIdsOptionRoomIds as $aRoomIdsOptionRoomId) {
                $aRoomByOptionFilter[] = array(
                    'accommodations' => Accommodations::where('id', '=', $aRoomIdsOptionRoomId)->get()->toArray(),
                    'amenities' => $this->getRoomByFilterId($aRoomIdsOptionRoomId)
                );
            }
            return $aRoomByOptionFilter;
            //------------------ Drop Down filter only End -------------

        }
    }

    /**
     * @param $RoomFilterId
     * @return mixed
     */
    public function getRoomByFilterId($RoomFilterId)
    {
        $model = DB::table($this->sCheckboxTable);
        $model->select([

            $this->sCheckboxTable . '.sCheckboxTitle',
        ]);
        $model->join($this->sCheckboxFilterTable, $this->sCheckboxTable . '.id', '=', $this->sCheckboxFilterTable . '.checkboxId');
        $model->where($this->sCheckboxFilterTable . '.roomId', $RoomFilterId);
        return $model->get()->toArray();
    }

    /**
     * @return mixed
     */
    public function getRoomTitleForSearchFilter()
    {
        $model = DB::table($this->sAccommodationsTable);
        $model->select([
            $this->sAccommodationsTable . '.id',
            $this->sAccommodationsTable . '.room_title'
        ]);
        return $model->get()->toArray();
    }

}