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


class AccommodationsRepository
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
     * @param array $aValidateData
     * @return mixed
     */
    public function validateDropDownTitle($aValidateData = array())
    {
        $rules_create = [
            'drop_down_title' => 'required|Min:2|Max:255',
        ];

        $messages = [
            'required' => trans('admin::validation.required'),
            'min' => trans('admin::validation.min'),
            'max' => trans('admin::validation.max')
        ];

        $validator = Validator::make($aValidateData, $rules_create, $messages);

        return $validator;
    }

    /**
     * @param array $aValidateData
     * @return mixed
     */
    public function validateAccommodations($aValidateData = array())
    {
        $rules_create = [
            'room_title' => 'required|Min:2|Max:255',
            'body' => 'required|Min:2',
            'booking_link' => 'Max:255'

        ];

        $messages = [
            'required' => trans('admin::validation.required'),
            'min' => trans('admin::validation.min')
        ];

        $validator = Validator::make($aValidateData, $rules_create, $messages);

        return $validator;
    }

    /**
     * @param array $aValidateData
     * @return mixed
     */
    public function validateCheckbox($aValidateData = array())
    {
        $rules_create = [
            'field_name_checkbox' => 'required|array'
        ];

        $messages = [
            'required' => trans('admin::validation.required')
        ];

        $validator = Validator::make($aValidateData, $rules_create, $messages);

        return $validator;
    }

    /**
     * @param $data
     * @return bool
     */
    public function createDropDownTitle($data)
    {
        if (!empty($data)) {
            DB::table($this->sDropDownTable)->insert(
                [
                    'sDropDownTitle' => $data,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]);
            $iDropDownId = \DB::table($this->sDropDownTable)->orderBy('id', 'desc')->first();
        }
        return (!empty($iDropDownId) ? $iDropDownId : false);
    }

    /**
     * @param array $aDataOption
     * @param $oDropDownId
     */
    public function createDropDownOption($aDataOption = array(), $oDropDownId)
    {
        $iDropDownId = $oDropDownId->id;
        if (!empty($iDropDownId) && is_array($aDataOption)) {
            foreach ($aDataOption as $sOption) {
                DB::table($this->sOptionTable)->insert(
                    [
                        'sOptionTitle' => $sOption,
                        'iDropDownId' => $iDropDownId,
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]);
            }
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteDropDown($id)
    {
        if (AccommodationsDropDown::where('id', '=', $id)->delete()) {
            if (DropDownOption::where('iDropDownId', '=', $id)->delete()) {
                return true;
            }
        }
    }

    /**
     * @return array
     */
    public function getAllDropDowns()
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
     * @param $iId
     * @return array
     */
    public function getDropdown($iId)
    {
        $aDropDowns = AccommodationsDropDown::where('id', '=', $iId)->get()->toArray();
        foreach ($aDropDowns as $aDropDown) {
            $aOptions = DropDownOption::where('iDropDownId', '=', $aDropDown['id'])->get()->toArray();
            $aAllDropDown[] = array(
                'id' => $aDropDown['id'],
                'sDropDownTitle' => $aDropDown['sDropDownTitle'],
                'option' => $aOptions
            );
        }
        return $aAllDropDown;
    }

    /**
     * @param $iId
     * @return array
     */
    public function getOptions($iId)
    {
        $aOptions = DropDownOption::where('iDropDownId', '=', $iId)->get()->toArray();
        if (!empty($aOptions)) {
            foreach ($aOptions as $aOption) {
                $aOptionArray[] = $aOption['id'];
            }
            return $aOptionArray;
        }
    }

    /**
     * @param array $aOption
     * @return bool
     */
    public function deleteOptions($aOption = array())
    {
        if (!empty($aOption)) {
            foreach ($aOption as $aOptionKey => $aOptionVal) {
                if (DropDownOption::where('id', '=', $aOptionVal)->delete()) {
                    return true;
                }
            }
        }
    }

    /**
     * @param array $aUpdateOptions
     */
    public function updateOptions($aUpdateOptions = array())
    {
        foreach ($aUpdateOptions as $aOptionKey => $aOptionVal) {
            \DB::table($this->sOptionTable)
                ->where('id', $aOptionKey)
                ->update(['sOptionTitle' => preg_replace('/\s+/', ' ', trim($aOptionVal)), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()]);
        }
    }

    /**
     * @param $iId
     * @param $sDropdownTitle
     */
    public function updateDropDown($iId, $sDropdownTitle)
    {
        \DB::table($this->sDropDownTable)
            ->where('id', $iId)
            ->update(['sDropDownTitle' => preg_replace('/\s+/', ' ', trim($sDropdownTitle)), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()]);
    }

    /**
     * @param $iDropDownId
     * @param array $aNewOptions
     */
    public function addNewOptionToDropDownUpdate($iDropDownId, $aNewOptions = array())
    {
        foreach ($aNewOptions as $sOption) {
            DB::table($this->sOptionTable)->insert(
                [
                    'sOptionTitle' => preg_replace('/\s+/', ' ', trim($sOption)),
                    'iDropDownId' => $iDropDownId,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]);
        }
    }

    //----------------------- checkbox data -----------------///

    /**
     * @param array $aValidateData
     * @return mixed
     */
    public function validateCheckboxData($aValidateData = array())
    {
        $rules_create = [
            'field_name_checkbox' => 'required|Min:2|Max:255'
        ];

        $messages = [
            'required' => trans('admin::validation.required')
        ];

        $validator = Validator::make($aValidateData, $rules_create, $messages);

        return $validator;
    }


    /**
     * @return array
     */
    public function getCheckbox()
    {
        $aCheckbox = Checkbox::all()->toArray();
        return $aCheckbox;
    }

    public function getCheckboxData($iId)
    {
        $bCheckbox = Checkbox::where('id', '=', $iId)->exists();
        if ($bCheckbox) {
            $aCheckbox = Checkbox::where('id', '=', $iId)->get()->toArray();
            return $aCheckbox;
        } else {
            return false;
        }
    }

    /**
     * @param array $aCheckboxData
     */
    public function createCheckbox($aCheckboxData = array())
    {
        if (is_array($aCheckboxData['field_name_checkbox'])) {
            foreach ($aCheckboxData['field_name_checkbox'] as $sCheckbox) {
                DB::table($this->sCheckboxTable)->insert(
                    [
                        'sCheckboxTitle' => $sCheckbox,
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]);
            }
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteCheckbox($id)
    {
        if (Checkbox::where('id', '=', $id)->delete()) {
            return true;
        }
    }

    /**
     * @param $iId
     * @param array $aUpdateData
     */
    public function updateCheckbox($iId, $aUpdateData = array())
    {
        \DB::table($this->sCheckboxTable)
            ->where('id', $iId)
            ->update([
                'sCheckboxTitle' => $aUpdateData['field_name_checkbox'],
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]);
    }

    //------------ createAccommodation -----------------
    /**
     * @param array $aData
     * @return mixed
     */
    public function createAccommodation($aData = array())
    {
        if (Accommodations::create($aData)) {
            $aRoom = Accommodations::all()->last()->toArray();
            return $aRoom;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteRoom($id)
    {
        if (Accommodations::where('id', '=', $id)->delete()) {
            CheckboxFilter::where('roomId', '=', $id)->delete();
            OptionFilter::where('roomId', '=', $id)->delete();
            return true;
        }
    }

    /**
     * @param $iRoomId
     * @return bool
     */
    public function getRoomDataById($iRoomId)
    {
        $bAccommodations = Accommodations::where('id', '=', $iRoomId)->exists();
        if ($bAccommodations) {
            $aAccommodations = Accommodations::where('id', '=', $iRoomId)->get()->toArray();
            return $aAccommodations;
        } else {
            return false;
        }
    }

    /**
     * @param $iRoomId
     * @param array $aRoomData
     */
    public function updateRoom($iRoomId, $aRoomData = array())
    {
        \DB::table($this->sAccommodationsTable)
            ->where('id', $iRoomId)
            ->update([
                'room_title' => $aRoomData['room_title'],
                'booking_link' => $aRoomData['booking_link'],
                'gallery_id' => $aRoomData['gallery_id'],
                'body' => $aRoomData['body'],
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]);
    }


    //-------------- Checkbox Filter ------------//

    /**
     * @param $iRoomId
     */
    public function deleteCheckboxFiltersForRoom($iRoomId)
    {
        CheckboxFilter::where('roomId', '=', $iRoomId)->delete();
    }

    /**
     * @param $iRoomId
     * @param array $aCheckbox
     */
    public function deleteInactiveCheckboxFilter($iRoomId, $aCheckbox = array())
    {
        foreach ($aCheckbox as $item) {
            CheckboxFilter::where(['roomId' => $iRoomId, 'id' => $item])->delete();
        }
    }

    /**
     * @param $iRoomId
     * @return mixed
     */
    public function getAllCheckboxFiltersForRoom($iRoomId)
    {
        $roomId = CheckboxFilter::where('roomId', '=', $iRoomId)->get()->toArray();
        return $roomId;
    }

    /**
     * @param $iRoomId
     * @param array $aCheckbox
     */
    public function addCheckboxFilterToRoom($iRoomId, $aCheckbox = array())
    {
        foreach ($aCheckbox as $item => $value) {
            DB::table($this->sCheckboxFilterTable)->insert(
                [
                    'checkboxId' => $value,
                    'roomId' => $iRoomId,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]);
        }
    }

    /**
     * @param null $iRoomId
     * @return array
     */
    public function getCheckboxFilterForRoomUpdate($iRoomId = null)
    {
        $model = DB::table($this->sCheckboxTable);
        $model->join($this->sCheckboxFilterTable, $this->sCheckboxTable . '.id', '=', $this->sCheckboxFilterTable . '.checkboxId');
        $model->where($this->sCheckboxFilterTable . '.roomId', $iRoomId);
        $aFilters = $model->get()->toArray();

        if (!empty($aFilters) && is_array($aFilters)) {
            foreach ($aFilters as $aFilter) {
                $newArray[] = $aFilter->checkboxId;
            }
            $list = implode(',', $newArray);

            $aResults = DB::select('SELECT * FROM ' . $this->sCheckboxTable . ' WHERE id NOT IN(' . $list . ')');

            return $aResultsCheckbox = array('aFilters' => $aFilters, 'aResults' => $aResults);
        } else {
            $aResults = DB::select('SELECT * FROM ' . $this->sCheckboxTable);
            return $aResultsCheckbox = array('aResults' => $aResults);
        }
    }

    //-------------- Drop Down Filter ------------//

    /**
     * @param $iRoomId
     * @return array
     */
    public function getDropDownFilterForRoomUpdate($iRoomId)
    {
        $aDropDowns = AccommodationsDropDown::all()->toArray();

        foreach ($aDropDowns as $aDropDown) {
            $aOptions = DropDownOption::where('iDropDownId', '=', $aDropDown['id'])->get()->toArray();
            $model = DB::table($this->sOptionTable);
            $model->select([
                $this->sOptionFilterTable . '.id',
                $this->sOptionTable . '.sOptionTitle',
                $this->sOptionFilterTable . '.roomId',
                $this->sOptionFilterTable . '.optionId'
            ]);
            $model->join($this->sOptionFilterTable, $this->sOptionTable . '.id', '=', $this->sOptionFilterTable . '.optionId');
            $model->where($this->sOptionFilterTable . '.roomId', $iRoomId);
            $model->where($this->sOptionTable . '.iDropDownId', $aDropDown['id']);
            $aSelectedOption = $model->get()->toArray();
            $aAllDropDown[] = array(
                'id' => $aDropDown['id'],
                'sDropDownTitle' => $aDropDown['sDropDownTitle'],
                'aSelectedOption' => $aSelectedOption,
                'option' => $aOptions
            );
        }
        return $aAllDropDown;
    }

    /**
     * @param $iRoomId
     * @param array $aDropDown
     */
    public function addDropDownFilterToRoom($iRoomId, $aDropDown = array())
    {
        foreach ($aDropDown as $item => $value) {
            DB::table($this->sOptionFilterTable)->insert(
                [
                    'optionId' => $value,
                    'roomId' => $iRoomId,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]);
        }
    }

    /**
     * @param $iRoomId
     * @return mixed
     */
    public function getOptionRoomFilter($iRoomId)
    {
        $aOptions = OptionFilter::where('roomId', '=', $iRoomId)->get()->toArray();
        return $aOptions;
    }

    /**
     * @param $iRoomId
     * @param array $aOptions
     */
    public function deleteInactiveRoomOptions($iRoomId, $aOptions = array())
    {
        foreach ($aOptions as $aOption) {
            OptionFilter::where(['roomId' => $iRoomId, 'id' => $aOption['id']])->delete();
        }
    }

    /**
     * @param $iRoomId
     * @param $aOption
     */
    public function addNewRoomOption($iRoomId, $aOption)
    {
        foreach ($aOption as $item => $iOptionId) {
            if ($iOptionId !== null) {
                DB::table($this->sOptionFilterTable)->insert(
                    [
                        'optionId' => $iOptionId,
                        'roomId' => $iRoomId,
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]);
            }
        }
    }

    /**
     * @param $iDropDownId
     * @param $iOptionId
     */
    public function deleteDropDownOption($iDropDownId, $iOptionId)
    {
        DropDownOption::where(['id' => $iOptionId, 'iDropDownId' => $iDropDownId])->delete();
        OptionFilter::where('id', '=', $iOptionId)->delete();
    }
}