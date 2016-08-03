            <div class="box-content">
                <?php if ($Config->get('last_login:enable') == true): ?>
                    <table>
                        <tbody></tbody>
                    </table>
                <?php else: ?>
                    <p>Disabled</p>
                <?php endif; ?>
            </div>