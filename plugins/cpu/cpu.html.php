<div class="box-content">
    <table class="firstBold">
        <tbody>
            <tr>
                <td>Model</td>
                <td id="cpu-model"></td>
            </tr>
            <tr>
                <td>Cores</td>
                <td id="cpu-num_cores"></td>
            </tr>
            <tr>
                <td>Speed</td>
                <td id="cpu-frequency"></td>
            </tr>
            <tr>
                <td>Cache</td>
                <td id="cpu-cache"></td>
            </tr>
            <tr>
                <td>Bogomips</td>
                <td id="cpu-bogomips"></td>
            </tr>
            <?php if ($Config->get('cpu:enable_temperature')): ?>
                <tr>
                    <td>Temperature</td>
                    <td id="cpu-temp"></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>