## Project's name: Aña añerando la tribu exploradora

<p align="center"> <img src="src/assets/img/HeaderLogo.png"/> </p>

## Table of contents

- [General info](#general-info)
- [Visual design](#visual-design)
- [Technologies](#technologies)
- [Setup frontend](#setup)
- [Versions](#versions)
- [Requirements](#requirements)
- [Game description](#game-description)
- [General characteristics](#general-characteristics)
- [Work methodology](#work-methodology)
- [Learnings](#learnings)
- [Next steps](#next-steps)


# General info

This is a project made for Ciudad Industrial del Valle del Nalón, S.A.U. (VALNALÓN). A Web Application as a Game to help kids between 3 to 6 six years old to learn words and vocabulary while they are playing a game. As a software team we had to build an app that allowed children to learn by doing through techonology.

# Visual Design

<img src="src/assets/gifs/Desktop-APP.gif"/>

<details><summary>Game</summary>

<img src="src/assets/gifs/Desktop-Game.gif"/>

</details>

# Technologies

|                                                                                                                                       Front End                                                                                                                                       |                                                                Back End                                                                 |                                                                                             Diseño y organización                                                                                              |
| :-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: | :-------------------------------------------------------------------------------------------------------------------------------------: | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
| <img src="https://github.com/Yelose/Yelose/blob/main/img/vscode.png"> <img src="https://github.com/Yelose/Yelose/blob/main/img/html.png"> <img src="https://github.com/Yelose/Yelose/blob/main/img/bootstrap.png"> <img src="https://github.com/Yelose/Yelose/blob/main/img/css.png"> | <img src="https://github.com/Yelose/Yelose/blob/main/img/php.png"> <img src="https://github.com/Yelose/Yelose/blob/main/img/mysql.png"> | <img src="https://github.com/Yelose/Yelose/blob/main/img/figma.png"> <img src="https://github.com/Yelose/Yelose/blob/main/img/jira.png"> <img src="https://github.com/Yelose/Yelose/blob/main/img/google.png"> |

## Setup

To run this project, install it locally using npm.

Clone the project

```bash
  git clone https://github.com/Equipo-Proton/la-tribu-exploradora-frontend.git
  git clone https://github.com/Equipo-Proton/la-tribu-exploradora-backend.git
```

Go to the project directory

```bash
  cd la-tribu-exploradora-frontend
```

Install dependencies

```bash
  npm install
```

Start the server

```bash
   npm run dev
```

# Versions

<p align="center"> <img src="src/assets/img/Versions.png"/> </p>
Our workflow in git was as follows, we all started from the Main branch with the same base. Implementations, changes and features were made in the Develop branch, they were tested in the Release branch and from Release versions with checkpoints were released and updated the Develop branch to continue development with all the features tested and thus not generate a cycle of bugs.

# Requirements

- [x] App will have some administrators (teachers) and some users (student groups). One user is a group of students.
- [x] Teachers can choose what games are active.
- [x] Users only can play active games.
- [x] We will build a game.
- [x] Plain design. Adaptative design (children).
- [x] Responsive design (tablet, computer and digital board).
- [x] Project will be 100% scalable and we will can add more games.
- [x] Project will be 100% tested.

## Game description

<ul>
<li>Game consists of numbers and letters.</li>
<li>Numbers available (0-9). Letters available (A-Z).</li>
<li>Admin's screen will start the game and users will have to guess the word, moving the letters.</li>
<li>Admin on their screen will decide if the answer is correct or wrong and the result will be visual.</li>
</ul>

# General characteristics

### Student Users

- They should be registered by a teacher user
- Your registration will have name, email and password
- Once registered they will be able to access the waiting screen until the teacher starts the game

### Teacher Users

- Teacher users can register students
- When you log in you will have access to your student panel
- They will be able to edit, register and delete students from their panel
- They may give way to the game to the students who have registered
- You will have access to a game panel where you will receive the answers of the students
- When a student finishes, the teacher can send a validation from his game panel
- The teacher can restart or end the game

### Admin

- Admin can register teachers
- The administrator will have a panel with all teachers
- The administrator can edit, register and delete teachers from his panel
- When a teacher is deleted, the students registered by this teacher will also be deleted

# Work methodology

- TDD, software test-driven development.
- We use Agile methodology in a Scrum framework.
- We separated the team into departments with front or back tasks, and we practised pair programming to connect the two parts of the project

# Learnings

- Create a web application from scratch with separated backend and frontend repository.
- Creation of views by components and modals for animations.
- Keyboard with draggable keys.
- Creation of a role system for users.

# Next steps

- [x] Caps button for the game keyboard
- [ ] Sound on the keyboard when pressing a letter
- [ ] Sound on the keyboard when receiving a notification
- [ ] Upload to production (Railway and/or other hosting)
- [ ] Implement modals for all notifications
- [ ] New game

# Documentation

- [Dailies](https://docs.google.com/document/d/1gGHcJYTPoZo_kbkBN_cQCpXgPu5JvurKWr8xd1vH7Lo/edit)
- [Presentation](https://view.genial.ly/63440989521ed300114a2b23/presentation-proyecto-ana)
- [Prototype](https://www.figma.com/file/HCGDAXOHXuOM567hPHBryR/Proyecto-Pedag%C3%B3gico?node-id=111%3A3)
- [UserFlow](https://www.figma.com/file/R3aFH6bPeETNHazU5k2zO4/Valnal%C3%B3n-User-Flow?node-id=0%3A1)

# Authors

| Nombre                 |     Roll      | <img src="https://github.com/Yelose/Yelose/blob/main/img/github.png" width="30px" height="30px"> |
| :--------------------- | :-----------: | :----------------------------------------------------------------------------------------------: |
| Guillermo García       | Web Developer |                                 https://github.com/guillerdev97                                  |
| Inma González          | Scrum Master  |                                   https://github.com/mimateach                                   |
| Juan Francisco Balseca | Product Owner |                                   https://github.com/sudobuda                                    |
| Kerim Ozkan            | Web Developer |                                      https://github.ozknkrm                                      |
| Miguel Salvador        | Web Developer |                             https://github.com/miguelsalvadorrguez85                             |
| Mario Jiménez          | Web Developer |                                    https://github.com/majifer                                    |

## Acknowledgements

- [Factoria F5](https://factoriaf5.org/?gclid=Cj0KCQjwy5maBhDdARIsAMxrkw0EF7pGqIr-Pip3eRLWDJxu5McgIqrV4kG3QZg4Z3u8YLn7CBHj-PkaAgfXEALw_wcB)
- [Awesome README](https://github.com/matiassingers/awesome-readme)
- [How to write a Good readme](https://bulldogjob.com/news/449-how-to-write-a-good-readme-for-your-github-project)

<p align="center"> <img src="https://github.com/Equipo-Proton/la-tribu-exploradora-frontend/blob/main/src/assets/img/ProtonTmLogo.png"/> </p>
