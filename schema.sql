CREATE TABLE bill (
  id INTEGER PRIMARY KEY,
  term INTEGER,
  session_period INTEGER,
  bill_type TEXT,
  proposal_source TEXT,
  ppg_bill_number TEXT,
  serial_number TEXT
);

CREATE TABLE bill_state (
  id INTEGER PRIMARY KEY,
  state_name TEXT,
  host TEXT
);

INSERT INTO bill_state (state_name, host) VALUES ('start', '');
INSERT INTO bill_state (state_name, host) VALUES ('end', '');

CREATE TABLE progress_link (
  id INTEGER PRIMARY KEY,
  bill_id INTEGER,
  link_index INTEGER,
  parent_state INTEGER,
  child_state INTEGER,
  p_host TEXT,
  p_session_period TEXT,
  p_date TEXT,
  FOREIGN KEY (bill_id) REFERENCES bill(id),
  FOREIGN KEY (parent_state) REFERENCES bill_state(id),
  FOREIGN KEY (child_state) REFERENCES bill_state(id)
);
