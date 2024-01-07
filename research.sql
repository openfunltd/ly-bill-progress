-- 調查在「委員提案」的提案中，「排入院會」狀態後會接哪些狀態

SELECT p.parent_state, s.state_name, p.child_state, count(p.child_state)
FROM progress_link p
INNER JOIN bill_state s
ON s.id = p.child_state
INNER JOIN bill b
ON p.bill_id = b.id
WHERE p.parent_state = 3 AND b.proposal_source = '委員提案'
GROUP BY p.child_state;
