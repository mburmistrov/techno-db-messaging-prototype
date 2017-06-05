<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\LoginHistory;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TestController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
	
	/**
     * This command fills loginHistory table with test data and outputs the result.
	 * @param bool $dryRun if true rows are not really inserted to table.
     * @param int $amount amount of generated rows.
     */
    public function actionFillLoginHistory($dryRun = false, $amount = 5)
    {
        for($i = 0; $i < 5; $i++){
        	
        }
    }
}
