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
        for ($i = 0; $i <= $amount; $i++) {
            $possiblePlatformIDs = [0, 1, 2]; //0 - Desktop, 1 - Android, 2 - iOS
            $platformID = array_rand($possiblePlatformIDs);

            // define possible OS types for UserAgent
            $osType = [];
            if ($platformID == 0) { // Windows, Linux, OS X
                $osType = ['Windows', 'Linux', 'OS X'];
            } elseif ($platformID == 1) { // Android
                $osType = ['Android'];
            } elseif ($platformID == 2) { // iOS
                $osType = ['iOS'];
            }

            $userAgent = UserAgent::random([
                'os_type' => $osType
            ]);

            $log = new LoginHistory();
            $log->userID = mt_rand(1, 20000);
            $log->loginIPAddress = "" . mt_rand(0,255) . "." . mt_rand(0,255)
                                    . "." . mt_rand(0,255) . "." . mt_rand(0,255);
            $log->platformID = $platformID;
            $log->deviceInfo = $userAgent;
            $log->dateTime = date("Y-m-d H:i:s");

            if (!$dryRun) {
                if ($log->save()) {
                    echo "iteration #" . $i . " saved\n";
                } else {
                    echo "save error\n";
                }
            } else {
                echo "iteration #" . $i . "\n";
            }
        }

        echo "\nfinished";
    }
}
