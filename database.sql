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


-- Brands.

-- Top 50 most popular brands on Twitter on March 7 2011. 
-- Source: http://tweetedbrands.com/

INSERT INTO "Brands" ("brandName") VALUES ('Twitter');
INSERT INTO "Brands" ("brandName") VALUES ('Youtube');
INSERT INTO "Brands" ("brandName") VALUES ('Facebook');
INSERT INTO "Brands" ("brandName") VALUES ('iPhone');
INSERT INTO "Brands" ("brandName") VALUES ('Google');
INSERT INTO "Brands" ("brandName") VALUES ('Apple');
INSERT INTO "Brands" ("brandName") VALUES ('Android');
INSERT INTO "Brands" ("brandName") VALUES ('Blackberry');
INSERT INTO "Brands" ("brandName") VALUES ('Myspace');
INSERT INTO "Brands" ("brandName") VALUES ('Amazon');
INSERT INTO "Brands" ("brandName") VALUES ('BBC');
INSERT INTO "Brands" ("brandName") VALUES ('Nokia');
INSERT INTO "Brands" ("brandName") VALUES ('MTV');
INSERT INTO "Brands" ("brandName") VALUES ('CNN');
INSERT INTO "Brands" ("brandName") VALUES ('Microsoft');
INSERT INTO "Brands" ("brandName") VALUES ('Starbucks');
INSERT INTO "Brands" ("brandName") VALUES ('Yahoo!');
INSERT INTO "Brands" ("brandName") VALUES ('eBay');
INSERT INTO "Brands" ("brandName") VALUES ('Nike');
INSERT INTO "Brands" ("brandName") VALUES ('Disney');
INSERT INTO "Brands" ("brandName") VALUES ('Sony');
INSERT INTO "Brands" ("brandName") VALUES ('Mashable');
INSERT INTO "Brands" ("brandName") VALUES ('Samsung');
INSERT INTO "Brands" ("brandName") VALUES ('BP');
INSERT INTO "Brands" ("brandName") VALUES ('Ford');
INSERT INTO "Brands" ("brandName") VALUES ('Canon');
INSERT INTO "Brands" ("brandName") VALUES ('Motorola');
INSERT INTO "Brands" ("brandName") VALUES ('Guardian');
INSERT INTO "Brands" ("brandName") VALUES ('KFC');
INSERT INTO "Brands" ("brandName") VALUES ('Toyota');
INSERT INTO "Brands" ("brandName") VALUES ('Techcrunch');
INSERT INTO "Brands" ("brandName") VALUES ('Coca-Cola');
INSERT INTO "Brands" ("brandName") VALUES ('Dell');
INSERT INTO "Brands" ("brandName") VALUES ('Gucci');
INSERT INTO "Brands" ("brandName") VALUES ('BMW');
INSERT INTO "Brands" ("brandName") VALUES ('British Airways');
INSERT INTO "Brands" ("brandName") VALUES ('Pepsi');
INSERT INTO "Brands" ("brandName") VALUES ('Intel');
INSERT INTO "Brands" ("brandName") VALUES ('McDonald');
INSERT INTO "Brands" ("brandName") VALUES ('Honda');
INSERT INTO "Brands" ("brandName") VALUES ('Adidas');
INSERT INTO "Brands" ("brandName") VALUES ('IBM');
INSERT INTO "Brands" ("brandName") VALUES ('Panasonic');
INSERT INTO "Brands" ("brandName") VALUES ('T-Mobile');
INSERT INTO "Brands" ("brandName") VALUES ('Spotify');
INSERT INTO "Brands" ("brandName") VALUES ('Lego');
INSERT INTO "Brands" ("brandName") VALUES ('Digg');
INSERT INTO "Brands" ("brandName") VALUES ('Mercedes');
INSERT INTO "Brands" ("brandName") VALUES ('IKEA');
INSERT INTO "Brands" ("brandName") VALUES ('VW');

COMMIT;
