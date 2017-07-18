<?php

namespace Module\Accommodations\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminModulesController;
use Module\Accommodations\Models\Repositories\AccommodationsRepository as AccommodationsRepository;

use Illuminate\Http\Request;


class AccommodationsAdminController extends AdminModulesController
{

    /**
     * @var AccommodationsRepository
     */
    private $oAccommodationsRepository;

    /**
     * AccommodationsAdminController constructor.
     * @param AccommodationsRepository $oAccommodationsRepository
     */
    public function __construct(AccommodationsRepository $oAccommodationsRepository)
    {
        $this->oAccommodationsRepository = $oAccommodationsRepository;

        parent::__construct(\Config::get('accommodations.setup'));
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex($id = null)
    {

        return view("accommodations-admin::admin/blank");
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getEditRoom($id = null)
    {
        if (!empty($id)) {

            //Get Accommodation
            $aAccommodationsData = $this->oAccommodationsRepository->getRoomDataById($id);

            $aCheckboxData = $this->oAccommodationsRepository->getCheckboxFilterForRoomUpdate($id);
            $aDropDownData = $this->oAccommodationsRepository->getDropDownFilterForRoomUpdate($id);
            if ($aAccommodationsData == false) {
                //If the Room don't exist redirect to room list page
                return redirect('/admin/modules/accommodations/index/');
            }

            $aData['aAccommodationsData'] = $aAccommodationsData;
            $aData['aCheckboxData'] = $aCheckboxData;
            $aData['aDropDownData'] = $aDropDownData;

            return view("accommodations-admin::admin/edit/edit-room", $aData);
        } else {
            return view("accommodations-admin::admin/blank");
        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postEditRoom(Request $request)
    {
        $aData = $request->all();
        $validator = $this->oAccommodationsRepository->validateAccommodations($aData);

        if (!$validator->fails()) {
            $iRoomId = $aData['updateRoom'];
            //Update the Accommodation Room Data
            $this->oAccommodationsRepository->updateRoom($iRoomId, $aData);

            //----------------------------- Update Checkbox Filter for the Room ---------------
            //Update Room Filters !!!!!!!!!!
            $aCheckboxData = $this->oAccommodationsRepository->getAllCheckboxFiltersForRoom($iRoomId);
            if (!empty($aData['Create_Checkbox_Filter']) && is_array($aData['Create_Checkbox_Filter'])) {

                if (!empty($aCheckboxData)) {
                    $this->oAccommodationsRepository->deleteInactiveCheckboxFilter($iRoomId, $aCheckboxData);
                }
                //Create all new checkbox filters for the Room
                $this->oAccommodationsRepository->addCheckboxFilterToRoom($iRoomId, $aData['Create_Checkbox_Filter']);
            }
            if (!isset($aData['Create_Checkbox_Filter'])) {
                $this->oAccommodationsRepository->deleteCheckboxFiltersForRoom($iRoomId);
            }
            //---------------------------------- End Checkbox Filter -------------------------------

            //--------------------------------- Update Drop Down Filter for the Room -----------------------

            $aRoomOptions = $this->oAccommodationsRepository->getOptionRoomFilter($iRoomId);

            if (!empty($aData['drop_down']) && is_array($aData['drop_down'])) {
                if (!empty($aRoomOptions)) {
                    $this->oAccommodationsRepository->deleteInactiveRoomOptions($iRoomId, $aRoomOptions);
                }

                $this->oAccommodationsRepository->addNewRoomOption($iRoomId, $aData['drop_down']);
            }
            //---------------------------------- End Drop Down Filter -------------------------------


            return redirect('/admin/modules/accommodations/index/');
        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddRoom($id = null)
    {
        $aData = array();
        $aCheckbox = $this->oAccommodationsRepository->getCheckbox();

        $aDropDown = $this->oAccommodationsRepository->getAllDropDowns();

        $aData['aDropDowns'] = $aDropDown;
        $aData['aCheckboxs'] = $aCheckbox;

        return view("accommodations-admin::admin/add-room", $aData);
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postAddRoom(Request $request)
    {
        $aData = $request->all();
        $validator = $this->oAccommodationsRepository->validateAccommodations($aData);

        if (!$validator->fails()) {
            $aRoomData = $this->oAccommodationsRepository->createAccommodation($aData);

            if (!empty($aData['Create_Checkbox_Filter']) && !empty($aRoomData['id'])) {
                //after the room is created add the checkbox filters
                $this->oAccommodationsRepository->addCheckboxFilterToRoom($aRoomData['id'], $aData['Create_Checkbox_Filter']);
            }

            if (!empty($aData['drop_down']) && !empty($aRoomData['id'])) {
                //after the room is created add the Drop Down filters
                $this->oAccommodationsRepository->addDropDownFilterToRoom($aRoomData['id'], $aData['drop_down']);
            }

            return redirect('/admin/modules/accommodations/index/');
        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    /**
     * @param null $iId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getRemoveRoom($iId = null)
    {
        $this->oAccommodationsRepository->deleteRoom($iId);
        return redirect('/admin/modules/accommodations/index/');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBulkActionRoom(Request $request)
    {
        if ($request->get('bulk_action_ids') != NULL) {
            $aIDs = explode(',', $request->get('bulk_action_ids'));
            //Proceed and delete the properties from the list
            if ($request->get('action') == 'bulk_delete') {
                foreach ($aIDs as $id) {
                    $this->getRemoveRoom($id);
                }
                \Session::flash('success', 'Menus was deleted');
            }
        }
        return redirect()->back();
    }


    //----------------------- select option ------------------------
    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddSelectOption($id = null)
    {
        return view("accommodations-admin::admin/add-select");
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSelectOptionList($id = null)
    {
        return view("accommodations-admin::admin/drop-down-list");
    }


    /**
     * @param Request $request
     * @return $this
     */
    public function postAddSelectOption(Request $request)
    {
        $aData = $request->all();
        $validator = $this->oAccommodationsRepository->validateDropDownTitle($aData);
        if (!$validator->fails()) {
            $iDropDownId = $this->oAccommodationsRepository->createDropDownTitle($aData['drop_down_title']);
            if (!empty($aData['field_name_option']) && is_array($aData['field_name_option'])) {
                $this->oAccommodationsRepository->createDropDownOption($aData['field_name_option'], $iDropDownId);
            }
            return redirect('/admin/modules/accommodations/select-option-list/');
        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getRemoveDropDown($id = null)
    {
        $this->oAccommodationsRepository->deleteDropDown($id);
        return redirect('/admin/modules/accommodations/select-option-list/');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBulkActionDropDown(Request $request)
    {
        if ($request->get('bulk_action_ids') != NULL) {
            $aIDs = explode(',', $request->get('bulk_action_ids'));
            //Proceed and delete the properties from the list
            if ($request->get('action') == 'bulk_delete') {
                foreach ($aIDs as $id) {
                    $this->getRemoveDropDown($id);
                }
                \Session::flash('success', 'Drop Down was deleted');
            }
        }
        return redirect()->back();
    }

    /**
     * @param null $iId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getEditDropdown($iId = null)
    {
        $aData = array();
        if (!empty($iId)) {
            $aDropdawnData = $this->oAccommodationsRepository->getDropdown($iId);
            $aData['aDropdawnData'] = $aDropdawnData;
            return view("accommodations-admin::admin/edit/edit-dropdown", $aData);
        } else {
            return redirect('/admin/modules/accommodations/select-option-list/');
        }
    }

    /**
     * @param null $iDropDownId
     * @param null $iOptionId
     */
    public function getRemoveDropDownOption($iDropDownId = null, $iOptionId = null)
    {
        $this->oAccommodationsRepository->deleteDropDownOption($iDropDownId, $iOptionId);
        return redirect('/admin/modules/accommodations/edit-dropdown/' . $iDropDownId);
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postEditDropdown(Request $request)
    {
        $aData = $request->all();

        $validator = $this->oAccommodationsRepository->validateDropDownTitle($aData);
        if (!$validator->fails()) {
            $aDropdownData = $this->oAccommodationsRepository->getOptions($aData['updateDropDown']);

            if (!empty($aData['option'])) {
                //get the option id
                foreach ($aData['option'] as $key => $val) {
                    $aNewOptions[] = $key;
                }

                if (!array_diff($aDropdownData, $aNewOptions)) {
                    //if array value is not different update the Option Value
                    $this->oAccommodationsRepository->updateOptions($aData['option']);

                } else {
                    //if the array value is different remove the old value from the database
                    foreach ($aDropdownData as $aDropdownKey => $aDropdownVal) {
                        foreach ($aNewOptions as $aNewOptionKey => $aNewOptionVal) {
                            if ($aDropdownVal != $aNewOptionVal) {
                                $aRemoveOption[$aNewOptionKey] = $aDropdownVal;
                            }
                        }
                    }
                    $this->oAccommodationsRepository->deleteOptions($aRemoveOption);
                    $this->oAccommodationsRepository->updateOptions($aData['option']);
                }
            } else {
                //If Option is empty delete all old options
                $this->oAccommodationsRepository->deleteOptions($aDropdownData);
            }

            //Update DropDown Title
            $this->oAccommodationsRepository->updateDropDown($aData['updateDropDown'], $aData['drop_down_title']);


            if (!empty($aData['field_name_option'])) {
                //Create new Options via DropDown update
                $this->oAccommodationsRepository->addNewOptionToDropDownUpdate($aData['updateDropDown'], $aData['field_name_option']);
            }

            return redirect('/admin/modules/accommodations/select-option-list/');
        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }



    //------------------- Checkbox ----------------//

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCheckboxList($id = null)
    {
        return view("accommodations-admin::admin/checkbox-list");
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddCheckbox($id = null)
    {
        return view("accommodations-admin::admin/add-checkbox");
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postAddCheckbox(Request $request)
    {
        $aData = $request->all();
        $validator = $this->oAccommodationsRepository->validateCheckbox($aData);
        $aData['field_name_checkbox'] = array_filter($aData['field_name_checkbox']);

        if (!empty($aData['field_name_checkbox'])) {

            foreach ($aData['field_name_checkbox'] as $key => $value) {
                $aData['field_name_checkbox'][$key] = preg_replace('/\s+/', ' ', trim($value));
            }
            $this->oAccommodationsRepository->createCheckbox($aData);
            return redirect('/admin/modules/accommodations/checkbox-list/');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getRemoveCheckbox($id = null)
    {
        $this->oAccommodationsRepository->deleteCheckbox($id);
        return redirect('/admin/modules/accommodations/checkbox-list/');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBulkActionCheckbox(Request $request)
    {
        if ($request->get('bulk_action_ids') != NULL) {
            $aIDs = explode(',', $request->get('bulk_action_ids'));
            //Proceed and delete the properties from the list
            if ($request->get('action') == 'bulk_delete') {
                foreach ($aIDs as $id) {
                    $this->getRemoveCheckbox($id);
                }
                \Session::flash('success', 'Menus was deleted');
            }
        }
        return redirect()->back();
    }

    /**
     * @param null $iId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getEditCheckBox($iId = null)
    {
        if (!empty($iId)) {

            $aCheckboxData = $this->oAccommodationsRepository->getCheckboxData($iId);

            if ($aCheckboxData) {
                $aData['aCheckboxData'] = $aCheckboxData;
                return view("accommodations-admin::admin/edit/edit-checkbox", $aData);
            } else {
                return redirect('/admin/modules/accommodations/checkbox-list/');
            }

        } else {
            return redirect('/admin/modules/accommodations/checkbox-list/');
        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postEditCheckbox(Request $request)
    {
        $aData = $request->all();

        $validator = $this->oAccommodationsRepository->validateCheckboxData($aData);

        if (!$validator->fails()) {

            $aData['field_name_checkbox'] = preg_replace('/\s+/', ' ', trim($aData['field_name_checkbox']));

            $this->oAccommodationsRepository->updateCheckbox($aData['updateCheckbox'], $aData);
            return redirect('/admin/modules/accommodations/checkbox-list/');

        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
