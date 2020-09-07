<?php
//READ
// 진행 중인 이벤트
function getOngoingEvent()
{
    $pdo = pdoSqlConnect();
    $query = "select event_idx, event_name, event_thumb, contents,
       case
           when timestampdiff(second, start_date, end_date) = 0 then '상시 이벤트'
           else concat(date_format(start_date, '%y.%m.%d '), '(', case DAYOFWEEK(start_date)
                                                                      when '1' then '일'
                                                                      when '2' then '월'
                                                                      when '3' then '화'
                                                                      when '4' then '수'
                                                                      when '5' then '목'
                                                                      when '6' then '금'
                                                                      when '7' then '토' end, ')', '~',
                       date_format(end_date, '%y.%m.%d '), '(', case DAYOFWEEK(start_date)
                                                                    when '1' then '일'
                                                                    when '2' then '월'
                                                                    when '3' then '화'
                                                                    when '4' then '수'
                                                                    when '5' then '목'
                                                                    when '6' then '금'
                                                                    when '7' then '토' end, ')')
           end as event_period
from Event
where end_date > now();";

    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 종료된 이벤트
function getEndEvent(){
    $pdo = pdoSqlConnect();
    $query = "select event_idx,
       event_name,
       event_thumb,
       contents,
       concat(date_format(start_date, '%y.%m.%d '), '(', case DAYOFWEEK(start_date)
                                                             when '1' then '일'
                                                             when '2' then '월'
                                                             when '3' then '화'
                                                             when '4' then '수'
                                                             when '5' then '목'
                                                             when '6' then '금'
                                                             when '7' then '토' end, ')', '~',
              date_format(end_date, '%y.%m.%d '), '(', case DAYOFWEEK(start_date)
                                                           when '1' then '일'
                                                           when '2' then '월'
                                                           when '3' then '화'
                                                           when '4' then '수'
                                                           when '5' then '목'
                                                           when '6' then '금'
                                                           when '7' then '토' end, ')')
           as event_period
from Event
where end_date < now();";

    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}
