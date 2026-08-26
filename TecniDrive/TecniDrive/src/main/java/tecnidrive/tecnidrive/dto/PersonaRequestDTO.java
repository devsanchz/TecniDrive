package tecnidrive.tecnidrive.dto;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;

public record PersonaRequestDTO(

        @NotBlank
        String primerNombre,

        String segundoNombre,

        @NotBlank
        String primerApellido,

        String segundoApellido,

        @NotBlank
        @Email
        String email,

        @NotBlank
        String password
) {
}