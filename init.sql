CREATE TABLE public.datasets
(
   name character varying(128), 
   geofield character varying(128), 
   token character(32),
   origin character varying(128),
   CONSTRAINT name PRIMARY KEY (name)
) 
WITH (
  OIDS = FALSE
)
;
COMMENT ON COLUMN public.datasets.name IS 'Dataset name';
COMMENT ON COLUMN public.datasets.geofield IS 'Geometry field';
COMMENT ON COLUMN public.datasets.token IS 'Access token';
COMMENT ON COLUMN public.datasets.origin IS 'Origin header';
