<!-- Corpo do site -->
<div class="container">
    <div id="contact-us" class="section">
        <div class="app-wrapper">
            <div class="contact-header">
                <div class="row contact-us">
                    <div class="col s12 m12 l4 sidebar-title">
                    </div>
                    <div class="col s12 m12 l8 form-header purple lighten-4">
                        <h6 class="form-header-text"><i class="material-icons"> mail_outline </i> Envie os dados para sua proposta!</h6>
                    </div>
                </div>
            </div>
            <!-- Contact Sidenav -->
            <div id="sidebar-list" class="row contact-sidenav">
                <div class="col s12 m12 l4">
                    <!-- Sidebar Area Starts -->
                    <div class="sidebar-left sidebar-fixed">
                        <div class="sidebar">
                            <div class="sidebar-content">
                                <div class="sidebar-menu list-group position-relative">
                                    <div class="sidebar-list-padding app-sidebar contact-app-sidebar" id="contact-sidenav">
                                        <ul id="slide-out">
                                            <div class="divider"></div>
                                            <a class="waves-effect" href="<?= DIRPAGE . "proposta" ?>">Criar Propostas</a>
                                            <div class="divider"></div>
                                            <a class="waves-effect" href="<?= DIRPAGE . "home" ?>">Monte sua</a>
                                            <div class="divider"></div>
                                            <a class="waves-effect" href="<?= DIRPAGE . "listarPropostas" ?>">Listar propostas</a>
                                            <div class="divider"></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar Area Ends -->
                </div>
                <!-- Formulário cotacao -->
                <div class="col s12 m12 l8 contact-form" id='beneficiario'>
                    <div class="row">
                        <form class="col s12" id="valid">
                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="propostas" id="propostas" class="browser-default" required>
                                        <option value="" readonly>Suas propostas:</option>
                                    </select><br>
                                    <input type="hidden" name="propostas_id">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <input id="nome_beneficiario" name="nome_beneficiario" minlength="3" type="text" class="validate" required>
                                    <label for="nome_beneficiario">Seu nome:</label>
                                </div>
                                <div class="input-field col m6 s12">
                                    <input id="idade_beneficiario" type="text" name="idade_beneficiario" class="validate" required>
                                    <label for="idade_beneficiario">Sua idade:</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12" id="resp"></div>

                            </div>
                            <div class="row">
                                <input class="waves-effect waves-light btn" id='env' type="submit" value="Cadastrar">
                            </div>
                        </form>
                    </div>
                    <hr>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>