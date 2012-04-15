BEGIN TRANSACTION;

PRAGMA encoding = "UTF-8";
PRAGMA foreign_keys = 1;

CREATE TABLE "Users"
(
    "userId" integer NOT NULL,
    "username" varchar(30) NOT NULL,
    "email" varchar(256) NOT NULL,
    "passwordHash" char(40) NOT NULL, -- For SHA-1 hashes.
    
    "firstName" varchar(30) NOT NULL,
    "lastName" varchar(50) NOT NULL,
    "gender" bool NOT NULL, -- 0: male, 1: female
    "birthdate" date NOT NULL,
    "description" varchar(300) NOT NULL,
    "minAgePref" integer NOT NULL,
    "maxAgePref" integer NOT NULL,
    "genderPref" bool, -- NULL in case of bisexuality
    
    -- Personality values, each is an integer between 0 and 100; E,S,F and P can be derived
    "personalityI" integer NOT NULL,
    "personalityN" integer NOT NULL,
    "personalityT" integer NOT NULL,
    "personalityJ" integer NOT NULL,
    
    -- Personality preferences
    "preferenceI" integer NOT NULL,
    "preferenceN" integer NOT NULL,
    "preferenceT" integer NOT NULL,
    "preferenceJ" integer NOT NULL,
    
    -- Contains the filename of an uploaded picture
    "picture" varchar(20),
    
    "admin" bool NOT NULL DEFAULT '0',
    
    PRIMARY KEY("userId"),
    UNIQUE("username"),
    UNIQUE("email")
);

CREATE TABLE "Brands"
(
    "brandName" varchar(30) NOT NULL,
    
    PRIMARY KEY ("brandName")
);

CREATE TABLE "UserBrands"
(
    "userId" integer NOT NULL,
    "brandName" varchar(30) NOT NULL,
    
    FOREIGN KEY ("userId")
      REFERENCES "Users"
       ON DELETE CASCADE,
    FOREIGN KEY ("brandName")
      REFERENCES "Brands"
        ON DELETE CASCADE,
        
    UNIQUE ("userId", "brandName")
);

CREATE TABLE "Likes"
(
    "userLiking" integer NOT NULL,
    "userLiked" integer NOT NULL,
    
    UNIQUE("userLiking", "userLiked"),
    FOREIGN KEY ("userLiking")
      REFERENCES "Users"
        ON DELETE CASCADE,
    FOREIGN KEY ("userLiked")
      REFERENCES "Users"
        ON DELETE CASCADE
);

-- Table should have exactly one row.
CREATE TABLE "Configuration"
(
    "similarityMeasure" integer NOT NULL, -- 0: Dice's, 1: Jaccard's, 2: cosine, 3: overlap
    "xFactor" float NOT NULL,
    "alpha" float NOT NULL
);

-- Default configuration.
INSERT INTO "Configuration" ("similarityMeasure", "xFactor", "alpha")
    VALUES (0, 0.5, 0.5);
    

-- Session table. Automaticallt managed by CodeIgniter's session class.
CREATE TABLE "Sessions"
(
    "session_id" varchar(40) DEFAULT '0' NOT NULL,
	"ip_address" varchar(16) DEFAULT '0' NOT NULL,
	"user_agent" varchar(120) NOT NULL,
	"last_activity" unsigned integer(10) DEFAULT 0 NOT NULL,
	"user_data" text NOT NULL,
	
	PRIMARY KEY (session_id)
);

CREATE INDEX "last_activity_idx"
    ON "Sessions" ("last_activity");
    
-- Admin user.
INSERT INTO "Users" 
    VALUES (
        1,
        'admin',
        'webmaster@dinges.nl',
        '105c075b400c0ac2ebacaf91da608e3f947a22e0', -- Password: puddingtaart
        'Admin',
        'The Admin',
        0,
        '1991-02-06',
        'I am the almighty administrator of this website! Bow and tremble before my mighty administrative capabilities!',
        18,
        25,
        1,
        50, 50, 50, 50, 50, 50, 50, 50,
        NULL,
        1
    );


-- Standard brands can be found in brands.sql.
-- TODO: Move them here.

COMMIT;
