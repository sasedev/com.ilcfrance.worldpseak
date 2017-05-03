ALTER TABLE "languages" DROP COLUMN "status";
ALTER TABLE "languages" ADD COLUMN "status" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "administrators" DROP COLUMN "locked";
ALTER TABLE "administrators" ADD COLUMN "locked" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "teachers" DROP COLUMN "locked";
ALTER TABLE "teachers" ADD COLUMN "locked" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "trainees" DROP COLUMN "locked";
ALTER TABLE "trainees" ADD COLUMN "locked" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "languages" DROP COLUMN "status";
ALTER TABLE "languages" ADD COLUMN "status" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "creationmail";
ALTER TABLE "timecredits" ADD COLUMN "creationmail" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "locked";
ALTER TABLE "timecredits" ADD COLUMN "locked" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "improvement1";
ALTER TABLE "timecredits" ADD COLUMN "improvement1" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "improvement2";
ALTER TABLE "timecredits" ADD COLUMN "improvement2" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "improvement3";
ALTER TABLE "timecredits" ADD COLUMN "improvement3" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "improvement4";
ALTER TABLE "timecredits" ADD COLUMN "improvement4" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "improvement5";
ALTER TABLE "timecredits" ADD COLUMN "improvement5" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "showreport";
ALTER TABLE "timecredits" ADD COLUMN "showreport" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "surveybegin";
ALTER TABLE "timecredits" ADD COLUMN "surveybegin" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "timecredits" DROP COLUMN "surveyend";
ALTER TABLE "timecredits" ADD COLUMN "surveyend" INT2 NOT NULL DEFAULT 1;


UPDATE trainees SET locked = 2 WHERE locked = 1;
UPDATE trainees SET locked = 1 WHERE locked = 0;


UPDATE teachers SET locked = 2 WHERE locked = 1;
UPDATE teachers SET locked = 1 WHERE locked = 0;


UPDATE administrators SET locked = 2 WHERE locked = 1;
UPDATE administrators SET locked = 1 WHERE locked = 0;


ALTER TABLE "courses" ADD COLUMN "coursphone" TEXT NULL;


UPDATE courses SET ctype = 0 WHERE ctype = 104;

UPDATE timecredits SET locked = 2 WHERE locked = 1;
UPDATE timecredits SET locked = 1 WHERE locked = 0;

ALTER TABLE "coursdocuments" ADD COLUMN "msg" TEXT NULL;

ALTER TABLE "timecredits" ADD COLUMN "buggy" INT2 NOT NULL DEFAULT 1;

ALTER TABLE "courses" ADD COLUMN "buggy" INT2 NOT NULL DEFAULT 1;


ALTER TABLE "teachers" ADD COLUMN "buggy" INT2 NOT NULL DEFAULT 1;


ALTER TABLE "trainees" ADD COLUMN "buggy" INT2 NOT NULL DEFAULT 1;

DELETE FROM "teachers" WHERE "codema" IS NOT NULL;
DELETE FROM "trainees" WHERE "codema" IS NOT NULL;

UPDATE "companies" SET "autoinc" = 1 WHERE "codema" IS NOT NULL;

DELETE FROM "courses" WHERE "codema" IS NOT NULL;

SELECT
    "guid",
    "dtcrea",
    "dtstartnotif", -- in the table
    "type",
    "typeid",
    "status"
FROM
    "adminnotifs"
GROUP BY
    "type",
    "typeid",
    "status"
HAVING
    COUNT(*) > 1;
