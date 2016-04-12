<div class="grid-100 tablet-grid-100 mobile-grid-100">
    <h2 class="no-top-padding">Scheduled Tasks</h2>
    <p>All scheduled tasks setup through TwistPHP are listed below. An indication of when the scheduler was last run can be found below.</p>
    <div>
        <dl>
            <dt>Status</dt><dd>Inactive</dd>
            <dt>Last Run</dt><dd>1 Minute Ago</dd>
            <dt>Frequency</dt><dd><select name="frequency">
                    <option value="1" selected>Every minute (Default)</option>
                    <option value="2">Every 2 minutes</option>
                    <option value="5">Every 5 minutes</option>
                    <option value="10">Every 10 minutes</option>
                    <option value="15">Every 15 minutes</option>
                    <option value="30">Every 30 minutes</option>
                    <option value="45">Every 45 minutes</option>
                    <option value="60">Every hour</option>
                </select></dd>
        </dl>
        <h3>Setup Scheduled Tasks</h3>
        <p>To get up and running with TwistPHP's Scheduled Tasks you will need to setup a CronJob that runs once every minute. Why once a minute? TwistPHP will then run the scripts if any that need to be run at that time, this gives the complete freedom for you to run tasks periodically at any interval or time.</p>
        <dl>
            <dt>Command</dt><dd>php /path/to/my/file.php</dd>
            <dt>Time</dt><dd>"* * * * *" Every Minute</dd>
        </dl>
        <p><strong>Note:</strong> If you are unable to run the command every minute set the script to run a regularly as possible and TwistPHP will compensate. Any system reliant on a more frequent task may not preform as well as expected.</p>
    </div>

    <table>
        <thead>
        <tr>
            <th>Task</th>
            <th>Frequency</th>
            <th>Command</th>
            <th>Tools</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>Cache Management</td>
                <td>Every Hour</td>
                <td>twist/crons/cache.php</td>
                <td><a href="">Log</a> <a href="">Disable</a></td>
            </tr>
            <tr>
                <td>Websocket Server</td>
                <td>Every Minute</td>
                <td>packages/Websockets/crons/server.php</td>
                <td><a href="">Log</a> <a href="">Disable</a></td>
            </tr>
            <tr>
                <td>Cache Management</td>
                <td>Every Hour</td>
                <td>twist/crons/cache.php</td>
                <td><a href="">Log</a> <a href="">Disable</a></td>
            </tr>
        </tbody>
    </table>
</div>