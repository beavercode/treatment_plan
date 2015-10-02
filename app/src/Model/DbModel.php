<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Model;

use UTI\Core\AbstractModel;

class DbModel extends AbstractModel
{
    // ::ctor($caller) in planpdfmodel and planstagesmodel
    // caller is used to access db data

//from PlanModel\PlanModel.php
    /**
     * Get form stages from DB.
     *
     * @state stub
     *
     * @return array
     */
    public function getStages()
    {
        //todo get from DB, using stub for now
        return [
            2  => 'Имплантация',
            10 => 'Ортодонтия'
        ];
    }

    /**
     * Get stage name from DB by id.
     *
     * @stub
     *
     * @param $stageId
     *
     * @return mixed
     */
    public function getStageById($stageId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            2  => 'Имплантация',
            10 => 'Ортодонтия'];

        return isset($dbResult[$stageId]) ? $dbResult[$stageId] : null;
    }

    /**
     * Get stage's pdf for merge.
     *
     * @stub
     *
     * @param $stageId
     *
     * @return string|null
     */
    public function getStagePdfById($stageId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            2  => 'pdf_term_implantation.pdf',
            10 => 'pdf_term_orthodontics.pdf'
        ];

        return isset($dbResult[$stageId]) ? $dbResult[$stageId] : null;
    }

    /**
     * Get full list of doctors from DB.
     *
     * @stub
     *
     * @return array
     */
    public function getDoctors()
    {
        //todo get from DB, using stub for now
        return [
            5  => 'Катаева В. Р.',
            24 => 'Воронин М. В.'
        ];
    }

    /**
     * Get doctor name from db by id.
     *
     * @state stub
     *
     * @param $doctorId
     *
     * @return string|null
     */
    public function getDoctorById($doctorId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            5  => 'Катаева В. Р.',
            24 => 'Воронин М. В.'];

        return isset($dbResult[$doctorId]) ? $dbResult[$doctorId] : null;
    }

    /**
     * Get doctor photo from db by id.
     *
     * @state stub
     *
     * @param $doctorId
     *
     * @return string|null
     */
    public function getDoctorPhotoById($doctorId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            5  => 'kataeva.jpg',
            24 => 'voronin.jpg'];

        return isset($dbResult[$doctorId]) ? $dbResult[$doctorId] : null;
    }


//from AuthModel/AuthModel.php
    /**
     * DB stub, get user data.
     *
     * todo Need secure mechanism with ACL for authentication and authorisation.
     *
     * @return array
     */
    protected function getLoginDataFromDB()
    {
        return [
            'login'    => 'admin',
            'password' => 123
        ];
    }
}
