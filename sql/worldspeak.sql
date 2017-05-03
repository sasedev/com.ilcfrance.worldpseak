DROP TABLE IF EXISTS "adminnotifs";

DROP TABLE IF EXISTS "teachernotifs";

DROP TABLE IF EXISTS "traineenotifs";

DROP TABLE IF EXISTS "coursdocuments";

DROP TABLE IF EXISTS "timecreditdocuments";

DROP TABLE IF EXISTS "courses";

DROP TABLE IF EXISTS "timecredits";

DROP TABLE IF EXISTS "teacheravailabilities";

DROP TABLE IF EXISTS "trainees_roles";

DROP TABLE IF EXISTS "trainees";

DROP TABLE IF EXISTS "companies";

DROP TABLE IF EXISTS "teachers_roles";

DROP TABLE IF EXISTS "teachers";

DROP TABLE IF EXISTS "administrators_roles";

DROP TABLE IF EXISTS "administrators";

DROP INDEX IF EXISTS "roles_u1";
DROP TABLE IF EXISTS "roles";

DROP TABLE IF EXISTS "languages";

DROP TABLE IF EXISTS "closeddays";

CREATE TABLE "closeddays" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"day"																DATE NOT NULL,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_closeddays" PRIMARY KEY ("id")
);

CREATE TABLE "languages" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"prefix"														TEXT NOT NULL,
	"name"															TEXT NULL,
	"direction"													TEXT NOT NULL DEFAULT 'LTR',
	"status"														INT2 NOT NULL DEFAULT 1,
	CONSTRAINT "pk_languages" PRIMARY KEY ("id")
);

INSERT INTO "languages" ("prefix", "name", "status") VALUES 
('fr', 'Français', true),
('en', 'English', false);

CREATE TABLE "roles" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"name"															TEXT NOT NULL,
	"description"									TEXT NULL,
	CONSTRAINT "pk_roles" PRIMARY KEY ("id")
);

CREATE UNIQUE INDEX "roles_u1" ON "roles" ("name");

INSERT INTO "roles" ("name", "description") VALUES
('ROLE_SUPER_SUPER_ADMIN', 'SuperSuperAdministrateur'),
('ROLE_SUPER_ADMIN', 'SuperAdministrateur'),
('ROLE_ADMIN', 'Adminstrateur'),
('ROLE_EXTERNAL_TEACHER', 'Formateur Externe'),
('ROLE_INTERNAL_TEACHER', 'Formateur Interne'),
('ROLE_TRAINEE', 'Stagiaire');


CREATE TABLE "administrators" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"username"													TEXT NOT NULL,
	"email"															TEXT NULL,
	"clearpassword"											TEXT NULL,
	"passwd"														TEXT NULL,
	"salt"															TEXT NULL,
	"recoverycode"											TEXT NULL,
	"recoveryexpiration"								TIMESTAMP WITH TIME ZONE NULL,
	"locked"														INT2 NOT NULL DEFAULT 1,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"logins"														INT8 NOT NULL DEFAULT 0,
	"lastlogin"													TIMESTAMP WITH TIME ZONE NULL,
	"lastactivity"											TIMESTAMP WITH TIME ZONE NULL,
	"lastname"													TEXT NULL,
	"firstname"													TEXT NULL,
	"sexe"															INT2 NULL,
	"birthday"													DATE NULL,
	"address"														TEXT NULL,
	"country"														TEXT NULL,
	"phone"															TEXT NULL,
	"mobile"														TEXT NULL,
	"preferedlang"											UUID NULL,
	"avatar"														UUID NULL,
	CONSTRAINT "pk_administrators" PRIMARY KEY ("id"),
	CONSTRAINT "fk_administrators_preferedlang" FOREIGN KEY ("preferedlang") REFERENCES "languages" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "administrators_roles" (
	"administrator"											UUID NOT NULL,
	"role"															UUID NOT NULL,
	CONSTRAINT "pk_administrators_roles" PRIMARY KEY ("administrator", "role"),
	CONSTRAINT "fk_administrators_roles_administrator" FOREIGN KEY ("administrator") REFERENCES "administrators" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT "fk_administrators_roles_role" FOREIGN KEY ("role") REFERENCES "roles" ("id") ON DELETE CASCADE ON UPDATE CASCADE
	
);

CREATE TABLE "teachers" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"username"													TEXT NOT NULL,
	"email"															TEXT NULL,
	"clearpassword"											TEXT NULL,
	"passwd"														TEXT NULL,
	"salt"															TEXT NULL,
	"recoverycode"											TEXT NULL,
	"recoveryexpiration"								TIMESTAMP WITH TIME ZONE NULL,
	"locked"														INT2 NOT NULL DEFAULT 1,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"logins"														INT8 NOT NULL DEFAULT 0,
	"lastlogin"													TIMESTAMP WITH TIME ZONE NULL,
	"lastactivity"											TIMESTAMP WITH TIME ZONE NULL,
	"lastname"													TEXT NULL,
	"firstname"													TEXT NULL,
	"sexe"															INT2 NULL,
	"birthday"													DATE NULL,
	"address"														TEXT NULL,
	"country"														TEXT NULL,
	"phone"															TEXT NULL,
	"mobile"														TEXT NULL,
	"coursphone"												TEXT NULL,
	"preferedlang"											UUID NULL,
	"avatar"														UUID NULL,
	"codema"														TEXT NULL,
	"registermail"											INT4 NOT NULL DEFAULT 1,
	"type"															INT4 NOT NULL DEFAULT 1,
	"ftype"															INT8 NOT NULL DEFAULT 100,
	CONSTRAINT "pk_teachers" PRIMARY KEY ("id"),
	CONSTRAINT "fk_teachers_preferedlang" FOREIGN KEY ("preferedlang") REFERENCES "languages" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "teachers_roles" (
	"teacher"														UUID NOT NULL,
	"role"															UUID NOT NULL,
	CONSTRAINT "pk_teachers_roles" PRIMARY KEY ("teacher", "role"),
	CONSTRAINT "fk_teachers_roles_teacher" FOREIGN KEY ("teacher") REFERENCES "teachers" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT "fk_teachers_roles_role" FOREIGN KEY ("role") REFERENCES "roles" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "teacheravailabilities" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"teacher"														UUID NOT NULL,
	"dtbegin"											TIMESTAMP WITH TIME ZONE NOT NULL,
	"dtend"												TIMESTAMP WITH TIME ZONE NOT NULL,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_teacheravailabilities" PRIMARY KEY ("id"),
	CONSTRAINT "fk_teacheravailabilities_teacher" FOREIGN KEY ("teacher") REFERENCES "teachers" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "companies" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"prefix"														TEXT NOT NULL,
	"name"															TEXT NULL,
	"codema"														TEXT NULL,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"autoinc"														INT8 NOT NULL DEFAULT 1,
	"service"														TEXT NULL,
	"address"														TEXT NULL,
	"postalcode"												TEXT NULL,
	"town"															TEXT NULL,
	"country"														TEXT NULL,
	CONSTRAINT "pk_companies" PRIMARY KEY ("id")
);

CREATE TABLE "trainees" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"username"													TEXT NOT NULL,
	"email"															TEXT NULL,
	"clearpassword"											TEXT NULL,
	"passwd"														TEXT NULL,
	"salt"															TEXT NULL,
	"recoverycode"											TEXT NULL,
	"recoveryexpiration"								TIMESTAMP WITH TIME ZONE NULL,
	"locked"														INT2 NOT NULL DEFAULT 1,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"logins"														INT8 NOT NULL DEFAULT 0,
	"lastlogin"													TIMESTAMP WITH TIME ZONE NULL,
	"lastactivity"											TIMESTAMP WITH TIME ZONE NULL,
	"lastname"													TEXT NULL,
	"firstname"													TEXT NULL,
	"sexe"															INT2 NULL,
	"birthday"													DATE NULL,
	"address"														TEXT NULL,
	"country"														TEXT NULL,
	"phone"															TEXT NULL,
	"mobile"														TEXT NULL,
	"preferedlang"											UUID NULL,
	"avatar"														UUID NULL,
	"company"														UUID NOT NULL,
	"codema"														TEXT NULL,
	"registermail"											INT4 NOT NULL DEFAULT 1,
	"cef"																TEXT NULL,
	"job"																TEXT NULL,
	"responsabilities"									TEXT NULL,
	"needs"															TEXT NULL,
	"outsideinterests"									TEXT NULL,
	"comments"													TEXT NULL,
	CONSTRAINT "pk_trainees" PRIMARY KEY ("id"),
	CONSTRAINT "fk_trainees_preferedlang" FOREIGN KEY ("preferedlang") REFERENCES "languages" ("id") ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "fk_trainees_company" FOREIGN KEY ("company") REFERENCES "companies" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "trainees_roles" (
	"trainee"														UUID NOT NULL,
	"role"															UUID NOT NULL,
	CONSTRAINT "pk_trainees_roles" PRIMARY KEY ("trainee", "role"),
	CONSTRAINT "fk_trainees_roles_trainee" FOREIGN KEY ("trainee") REFERENCES "trainees" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT "fk_trainees_roles_role" FOREIGN KEY ("role") REFERENCES "roles" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "timecredits" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"trainee"														UUID NOT NULL,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"ftype"															INT8 NOT NULL DEFAULT 100,
	"totalhours"												FLOAT8 NOT NULL,
	"reservedhours"											FLOAT8 NOT NULL DEFAULT 0,
	"donehours"													FLOAT8 NOT NULL DEFAULT 0,
	"losthours"													FLOAT8 NOT NULL DEFAULT 0,
	"deadline"													DATE NULL,
	"level"															INT4 NOT NULL DEFAULT 0,
	"locked"														INT2 NOT NULL DEFAULT 1,
	"cefbegin"													TEXT NULL,
	"cefend"														TEXT NULL,
	"objectives"												TEXT NULL,
	"comments"													TEXT NULL,
	"creationmail"											INT4 NOT NULL DEFAULT 1,
	"forcedkpihomeworkperformed"				INT4 NULL,
	"forcedkpiparticipation"						INT4 NULL,
	"forcedkpivocabularyretention"			INT4 NULL,
	"forcedkpicorrectionconsideration"	INT4 NULL,
	"progress1"													INT4 NULL,
	"progress2"													INT4 NULL,
	"progress3"													INT4 NULL,
	"progress4"													INT4 NULL,
	"progress5"													INT4 NULL,
	"progress6"													INT4 NULL,
	"progress7"													INT4 NULL,
	"leveldesc1"												INT4 NULL,
	"leveldesc2"												INT4 NULL,
	"leveldesc3"												INT4 NULL,
	"leveldesc4"												INT4 NULL,
	"leveldesc5"												INT4 NULL,
	"leveldesc6"												INT4 NULL,
	"leveldesc7"												INT4 NULL,
	"improvement1"											INT2 NOT NULL DEFAULT 1,
	"improvement2"											INT2 NOT NULL DEFAULT 1,
	"improvement3"											INT2 NOT NULL DEFAULT 1,
	"improvement4"											INT2 NOT NULL DEFAULT 1,
	"improvement5"											INT2 NOT NULL DEFAULT 1,
	"lastteacherreport"									TEXT NULL,
	"showreport"												INT2 NOT NULL DEFAULT 1,
	"surveybeginq01"										INT4 NULL,
	"surveybeginq02"										INT4 NULL,
	"surveybeginq03"										INT4 NULL,
	"surveybeginq04"										INT4 NULL,
	"surveybeginq05"										INT4 NULL,
	"surveybeginq06"										INT4 NULL,
	"surveybeginq07"										TEXT NULL,
	"surveybeginq08"										TEXT NULL,
	"surveybegin"												INT2 NOT NULL DEFAULT 1,
	"surveyendq01"											INT4 NULL,
	"surveyendq02"											INT4 NULL,
	"surveyendq03"											INT4 NULL,
	"surveyendq04"											INT4 NULL,
	"surveyendq05"											INT4 NULL,
	"surveyendq06"											INT4 NULL,
	"surveyendq07"											INT4 NULL,
	"surveyendq08"											INT4 NULL,
	"surveyendq09"											INT4 NULL,
	"surveyendq10"											TEXT NULL,
	"surveyend"													INT2 NOT NULL DEFAULT 1,
	CONSTRAINT "pk_timecredits" PRIMARY KEY ("id"),
	CONSTRAINT "fk_timecredits_trainee" FOREIGN KEY ("trainee") REFERENCES "trainees" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "timecreditdocuments" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"timecredit"												UUID NOT NULL,
	"teachingresource"									UUID NULL,
	"msg"																TEXT NULL,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"creationmail"											INT4 NOT NULL DEFAULT 1,
	CONSTRAINT "pk_timecreditdocuments" PRIMARY KEY ("id"),
	CONSTRAINT "fk_timecreditdocuments_timecredit" FOREIGN KEY ("timecredit") REFERENCES "timecredits" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "courses" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"codema"														TEXT NULL,
	"timecredit"												UUID NOT NULL,
	"teacher"														UUID NULL,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"ctype"															INT8 NOT NULL DEFAULT 0,
	"status"														INT4 NOT NULL DEFAULT 1,
	"start"															TIMESTAMP WITH TIME ZONE NOT NULL,
	"duration"													INT8 NOT NULL DEFAULT 60,
	"coursphone"												TEXT NULL,
	"kpihomeworkperformed"							INT4 NULL,
	"kpiparticipation"									INT4 NULL,
	"kpivocabularyretention"						INT4 NULL,
	"kpicorrectionconsideration"				INT4 NULL,
	"correctionvocabulairy"							TEXT NULL,
	"correctionstructure"								TEXT NULL,
	"correctionprononciation"						TEXT NULL,
	"progress"													TEXT NULL,
	"comments"													TEXT NULL,
	CONSTRAINT "pk_courses" PRIMARY KEY ("id"),
	CONSTRAINT "fk_courses_timecredit" FOREIGN KEY ("timecredit") REFERENCES "timecredits" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT "fk_courses_teacher" FOREIGN KEY ("teacher") REFERENCES "teachers" ("id") ON DELETE CASCADE ON UPDATE SET NULL
);

CREATE TABLE "coursdocuments" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"cours"															UUID NOT NULL,
	"traineefile"												UUID NOT NULL,
	"msg"																TEXT NULL,
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_coursdocuments" PRIMARY KEY ("id"),
	CONSTRAINT "fk_coursdocuments_cours" FOREIGN KEY ("cours") REFERENCES "courses" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "traineenotifs" (
	"id"																UUID NOT NULL DEFAULT uuid_generate_v4(),
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"trainee"														UUID NOT NULL,
	"dtstartnotif"											TIMESTAMP WITH TIME ZONE NULL,
	"status"														INT4 NOT NULL DEFAULT 1,
	"typeid"														INT4 NOT NULL,
	"timecredit"													UUID NULL,
	"cours"															UUID NULL,
	CONSTRAINT "pk_traineenotifs" PRIMARY KEY ("id"),
	CONSTRAINT "fk_traineenotifs_trainee" FOREIGN KEY ("trainee") REFERENCES "trainees" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT "fk_traineenotifs_timeCredit" FOREIGN KEY ("timecredit") REFERENCES "timecredits" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT "fk_traineenotifs_cours" FOREIGN KEY ("cours") REFERENCES "courses" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "teachernotifs" (
	"id"															UUID NOT NULL DEFAULT uuid_generate_v4(),
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"teacher"														UUID NOT NULL,
	"dtstartnotif"													TIMESTAMP WITH TIME ZONE NULL,
	"status"														INT4 NOT NULL DEFAULT 1,
	"typeid"														INT4 NOT NULL,
	"timecredit"													UUID NULL,
	"cours"															UUID NULL,
	CONSTRAINT "pk_teachernotifs" PRIMARY KEY ("id"),
	CONSTRAINT "fk_teachernotifs_teacher" FOREIGN KEY ("teacher") REFERENCES "teachers" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT "fk_teachernotifs_timeCredit" FOREIGN KEY ("timecredit") REFERENCES "timecredits" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT "fk_teachernotifs_cours" FOREIGN KEY ("cours") REFERENCES "courses" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "adminnotifs" (
	"id"															UUID NOT NULL DEFAULT uuid_generate_v4(),
	"dtcrea"														TIMESTAMP WITH TIME ZONE NULL,
	"dtstartnotif"													TIMESTAMP WITH TIME ZONE NULL,
	"status"														INT4 NOT NULL DEFAULT 1,
	"typeid"														INT4 NOT NULL,
	"timecredit"													UUID NULL,
	CONSTRAINT "pk_adminnotifs" PRIMARY KEY ("id"),
	CONSTRAINT "fk_adminnotifs_timeCredit" FOREIGN KEY ("timecredit") REFERENCES "timecredits" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


