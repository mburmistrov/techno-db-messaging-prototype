<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\LoginHistory;
use Campo\UserAgent;

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
     * This command fills loginHistory tables and outputs the result.
     * @param string $dryRun if true then rows are not really inserted to db table.
     * @param string $amount number of rows to insert.
     */
    public function actionFillLoginHistory($dryRun = false, $amount = 5)
    {
        echo UserAgent::random(); exit;
        for ($i = 0; $i <= $amount; $i++) {
            $log = new LoginHistory();
		    $log->userID = mt_rand(1, 20000);
            $log->loginIPAddress = "".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
            $log->platformID = mt_rand(1, 4);
            $log->deviceInfo;
            
        }
    }
}
