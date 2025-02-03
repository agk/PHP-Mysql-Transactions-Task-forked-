<?php

/**
 * Return list of users.
 */
function get_users($conn)
{
    // TODO: implement
    $resQuery = $conn->query("SELECT id, `name` FROM `users`");
    $users = array();
    while ($row = $resQuery->fetch()) {
        $users[$row['id']] = $row['name'];
    }
    return $users;
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn)
{    
    // выполняем запрос к БД - выбираем помесячно суммы транзакций приходов на счета
    $statementQueryAccountTo = $conn->query("
        SELECT 
            SUM(t.amount) AS `sum`,
            strftime('%m', t.trdate) AS `month`
        FROM `users` u
        LEFT JOIN `user_accounts` AS ua ON u.id = ua.user_id
        LEFT JOIN `transactions` AS t ON t.account_to = ua.id
        WHERE u.id = $user_id
        GROUP BY `month`
    ");
    $resQueryAccountTo = array();
    while ($row = $statementQueryAccountTo->fetch()) {
        if ($row['sum'] > 0) $resQueryAccountTo[$row['month']] = $row['sum'];
    }
    
    // выполняем запрос к БД - выбираем помесячно суммы транзакций убытий со счетов
    $statementQueryAccountFrom = $conn->query("
        SELECT 
            SUM(t.amount) AS `sum`,
            strftime('%m', t.trdate) AS `month`
        FROM `users` u
        LEFT JOIN `user_accounts` AS ua ON u.id = ua.user_id
        LEFT JOIN `transactions` AS t ON t.account_from = ua.id
        WHERE u.id = $user_id
        GROUP BY `month`
    ");
    $resQueryAccountFrom = array();
    while ($row = $statementQueryAccountFrom->fetch()) {
        if ($row['sum'] > 0) $resQueryAccountFrom[$row['month']] = $row['sum'];
    }
    $res = array();
    $resQueryArray = count($resQueryAccountTo) > count($resQueryAccountFrom) 
                    ? $resQueryAccountTo 
                    : $resQueryAccountFrom;

    foreach ($resQueryArray as $m => $value) {
        $sumAccountTo = isset($resQueryAccountTo[$m]) ? ($resQueryAccountTo[$m]) : 0;
        $res[$m] = $sumAccountTo - $resQueryAccountFrom[$m];
    }
    
    return $res;

}