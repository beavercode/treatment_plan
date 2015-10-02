<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Model\PlanModel\Exceptions;

use UTI\Core\Exceptions\ModelException;

/**
 * PlanStagesModel is class which operates with ajax it throws an errors that i cant see in browser.
 *
 * Thus i had created this class to always log errors of this type.
 *
 * todo: Proper exception(s) extended from SPL exceptions
 *
 * @package UTI
 */
class PlanStagesModelException extends ModelException
{
}
