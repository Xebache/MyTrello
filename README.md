<h1 align="center">
  <img alt="Logo" src="https://github.com/Xebache/MyTrello/blob/main/docs/trello.svg" width="200" />
</h1>

## Project

 `php` and `javascript` Web application inspired by Trello.

  This application was developed in an academic course during my second year of the bachelor in computed science at EPFC.  
  It uses a homemade framework provided by the teacher to simplify some steps (<a href="https://github.com/Xebache/MyTrello/tree/main/MyTrello!/framework">framework</a>) as well as a MVC pattern.

## Description

 As mentionned before, the application is Trello-like and is very similar in its utilisation.
 The user creates and manages boards, columns and cards. 

 A registered user creates a board; he becomes the owner of that board and can manage it entirely.
 He may invite other users to collaborate on his board. These collaborators can edit or delete columns and cards.

 The admins have access to all the boards of the database and have the right to edit, delete them.
 They also manage a list of all the users, creating, editing or deleting them.

 Columns and cards are draggable, all contents are editable (each modification is saved in the database)
  
 If a card has an expiration date, it appears in the calendar. 
  
## Screenshots

 <div display="flex" flex-direction="row" justify-content="space-around" align-items="center" flex-wrap="wrap" align="center">
  <img alt="Screenshot" src="https://github.com/Xebache/MyTrello/blob/main/docs/login.png" height="100" />
  <img alt="Screenshot" src="https://github.com/Xebache/MyTrello/blob/main/docs/boards.png" height="100" />
  <img alt="Screenshot" src="https://github.com/Xebache/MyTrello/blob/main/docs/board.png" height="100" />
  <img alt="Screenshot" src="https://github.com/Xebache/MyTrello/blob/main/docs/collaborators.png" height="100" />
  <img alt="Screenshot" src="https://github.com/Xebache/MyTrello/blob/main/docs/card.png" height="100" />
</div>

## Miscellaneous

 ### Installation

 The site is not deployed but can run locally.
 Import the database in phpMyAdmin. In `config/prod.ini`, make sure `mysql_path` correspond to the local Web server. Type http://localhost/trello/MyTrello!/setup in a browser to install the database.

 ### List of users